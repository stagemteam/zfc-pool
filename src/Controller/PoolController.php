<?php
/**
 * @package Stagem_Pool
 * @author Vlad Kozak <vk@stagem.com.ua>
 * @datetime: 15.08.2016 13:41
 */
namespace Stagem\ZfcPool\Controller;

use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;
use Popov\ZfcCore\Service\ServiceManagerAwareTrait;
use Popov\ZfcCore\Controller\DeleteActionAwareTrait;
use Stagem\ZfcPool\Form\PoolForm;

class PoolController extends AbstractActionController
{
    use ServiceManagerAwareTrait;
    use DeleteActionAwareTrait;

    public function indexAction()
    {
        $sm = $this->getServiceManager();
        $pools = $this->getService()->getRepository()->getPools();
        /** @var \Stagem\ZfcPool\Block\Grid\PoolGrid $poolGrid */
        $poolGrid = $sm->get('PoolGrid');
        /** @var \ZfcDatagrid\Datagrid $poolDataGrid */
        $poolDataGrid = $poolGrid->getDataGrid();
        $poolDataGrid->setDataSource($pools);
        $poolDataGrid->render();
        $poolDataGridVm = $poolDataGrid->getResponse();

        return $poolDataGridVm;
    }

    public function createAction()
    {
        return $viewModel = $this->editAction();
    }

    public function viewAction()
    {
        $route = $this->getEvent()->getRouteMatch();
        $service = $this->getService();
        $pool = ($pool = $service->find($id = (int) $route->getParam('id')))
            ? $pool
            : $service->getObjectModel();

        return (
            new ViewModel(
                [
                    'pool' => $pool,
                ]
            )
        );
    }

    function editAction()
    {
        $request = $this->getRequest();
        $route = $this->getEvent()->getRouteMatch();
        $service = $this->getService();
        $fm = $this->getServiceManager()->get('FormElementManager');
        /** @var PoolService $poolService */
        $poolService = ($poolService = $service->find($id = (int) $route->getParam('id')))
            ? $poolService
            : $service->getObjectModel();
        /** @var PoolForm $form */
        $form = $fm->get(PoolForm::class);
        $form->bind($poolService);
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $this->getService()->save($poolService);
                $msg = 'Pool был успешно сохранен';
                $this->flashMessenger()->addSuccessMessage($msg);

                return $this->redirect()->toRoute(
                    'default', [
                        'controller' => 'pool',
                        'action' => 'index',
                    ]
                )
                    ;
            } else {
                $msg = 'Форма не валидна. Проверьте значение и внесите коррективы';
                $this->flashMessenger()->addSuccessMessage($msg);
            }
        }

        return new ViewModel(
            [
                'form' => $form,
            ]
        );
    }

    /**
     * @return PoolService
     */
    public function getService()
    {
        return $this->getServiceManager()->get('PoolService');
    }
}
