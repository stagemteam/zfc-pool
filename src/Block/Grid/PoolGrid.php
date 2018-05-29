<?php
/**
 * @package Stagem_Pool
 * @author Vlad Kozak <vk@stagem.com.ua>
 * @datetime: 15.08.2016 13:41
 */
namespace Stagem\ZfcPool\Block\Grid;

use DoctrineModule\Persistence\ProvidesObjectManager;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use ZfcDatagrid\Column;
use ZfcDatagrid\Column\Style;
use ZfcDatagrid\Column\Type;
use ZfcDatagrid\Action;
use Popov\ZfcDataGrid\Block\AbstractGrid;

class PoolGrid extends AbstractGrid implements ObjectManagerAwareInterface
{
    use ProvidesObjectManager;

    protected $createButtonTitle = 'Добавить';

    protected $backButtonTitle = '';

    public function init()
    {
        $grid = $this->getDataGrid();
        $route = $this->getRouteMatch();
        $view = $this->getRenderer();
        $grid->setRendererName('jqGrid');
        $grid->setId('pool');
        $grid->setTitle('Пациенты');
        $colId = new Column\Select('id', 'pool');
        $colId->setIdentity();
        $grid->addColumn($colId);
        $deleteUrl = $view->url(
            $route->getMatchedRouteName(), [
            'controller' => $route->getParam('controller'),
            'action' => 'delete',
        ]
        );
        $massAction = new Action\Mass();
        $massAction->setTitle('Удалить');
        $massAction->setLink($deleteUrl);
        $grid->addMassAction($massAction);
        $viewUrl = $view->url(
            $route->getMatchedRouteName(), [
            'controller' => $route->getParam('controller'),
            'action' => 'edit',
        ]
        );
        $formatter = <<<FORMATTER
function (value, options, rowObject) {
	return '<a href="{$viewUrl}/' + rowObject.pool_id + '" >' + value + '</a>';
}
FORMATTER;
        $col = new Column\Select('name', 'pool');
        $col->setLabel('Назва');
        $col->setTranslationEnabled();
        $col->setWidth(2);
        $col->setRendererParameter('formatter', $formatter, 'jqGrid');
        $grid->addColumn($col);
        $col = new Column\Select('address', 'pool');
        $col->setLabel('Аддрес');
        $col->setTranslationEnabled();
        $col->setWidth(2);
        $grid->addColumn($col);
        $col = new Column\Select('description', 'pool');
        $col->setLabel('Описание');
        $col->setTranslationEnabled();
        $col->setWidth(2);
        $grid->addColumn($col);

        return $grid;
    }
    /*public function initToolbar() {
        $grid = $this->getDataGrid();
        $toolbar = $this->getToolbar();
        $route = $this->getRouteMatch();

        $grid->getResponse()->setVariable('exportRenderers', ['PHPExcel' => 'Excel']);

        return $toolbar;
    }*/
}