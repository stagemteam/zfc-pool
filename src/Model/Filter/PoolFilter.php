<?php
/**
 * The MIT License (MIT)
 * Copyright (c) 2018 Serhii Popov
 * This source file is subject to The MIT License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/MIT
 *
 * @category Stagem
 * @package Stagem_ZfcPool
 * @author Serhii Popov <popow.serhii@gmail.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Stagem\ZfcPool\Model\Filter;

use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Persisters\Entity\BasicEntityPersister;
use Stagem\ZfcPool\Model\Annotation\PoolAware;
use Stagem\ZfcPool\Service\PoolService;
use Stagem\ZfcPool\PoolHelper;

class PoolFilter extends SQLFilter
{
    /**
     * @var AnnotationReader
     */
    protected $reader;

    /**
     * @var PoolHelper
     */
    protected $poolHelper;

    public function setPoolHelper(PoolHelper $poolHelper)
    {
        $this->poolHelper = $poolHelper;
    }

    public function getPoolHelper()
    {
        return $this->poolHelper;
    }

    public function setAnnotationReader($reader)
    {
        $this->reader = $reader;
    }

    /**
     * Gets the SQL query part to add to a query.
     *
     * @param ClassMetaData $targetEntity
     * @param string $targetTableAlias
     *
     * @return string The constraint SQL if there is available, empty string otherwise.
     */
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if (empty($this->reader)) {
            return '';
        }

        // The Doctrine filter is called for any query on any entity
        // Check if the current entity is "pool aware" (marked with an annotation)
        $poolAware = $this->reader->getClassAnnotation(
            $targetEntity->getReflectionClass(),
            PoolAware::class
        );
        if (!$poolAware) {
            return '';
        }

        if (!$poolHelper = $this->getPoolHelper()) {
            return '';
        }

        $fieldName = $poolAware->getFieldName();
        $pool = $poolHelper->current();
        if (empty($fieldName) || ($pool->getId() === PoolService::POOL_ADMIN)) {
            return '';
        }

        if (!$sqlWalker = $this->getSqlWalker()) {
            return '';
        }

        if (!isset($targetEntity->getAssociationMappings()[$fieldName])) {
            return '';
        }

        $mapping = $targetEntity->getAssociationMappings()[$fieldName];

        if (isset($mapping['joinColumns'])) {
            // oneToMany relation detected
            $table = $targetEntity->getTableName();
            $columnName = $mapping['joinColumns'][0]['name'];
            $dqlAlias = constant($targetEntity->getName() . '::MNEMO');
        } elseif (isset($mapping['joinTable'])) {
            // manyToMany relation detected
            $dqlAlias = constant($mapping['targetEntity'] . '::MNEMO');
            $component = $sqlWalker->getQueryComponent($dqlAlias);

            // Only main entity in query is interesting for us,
            // otherwise do not apply any filter
            if ($component['parent']) {
                return '';
            }
            $table = $mapping['joinTable']['name'];
            $columnName = $mapping['joinTable']['inverseJoinColumns'][0]['name'];
        } else {
            return '';
        }

        $tableAlias = ($sqlWalker instanceof BasicEntityPersister)
            ? $targetTableAlias // $repository->getBy() has been called
            : $sqlWalker->getSQLTableAlias($table, $dqlAlias);

        $query = sprintf('%s.%s = %s', $tableAlias, $columnName, $this->getConnection()->quote($pool->getId()));

        return $query;
    }

    /**
     * Get SqlWalker with debug_backtrace
     *
     * @see https://stackoverflow.com/questions/346703
     * @return null|SqlWalker
     */
    protected function getSqlWalker()
    {
        $caller = debug_backtrace();
        $caller = $caller[2];

        if (isset($caller['object'])) {
            return $caller['object'];
        }

        return null;
    }
}