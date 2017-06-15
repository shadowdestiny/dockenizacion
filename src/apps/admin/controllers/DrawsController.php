<?php


namespace EuroMillions\admin\controllers;

use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\admin\vo\dto\DrawDTO;
use EuroMillions\admin\services\MaintenanceDrawService;
use EuroMillions\shared\components\widgets\PaginationWidget;
use EuroMillions\web\entities\EuroMillionsDraw;
use Money\Currency;
use Money\Money;

class DrawsController extends AdminControllerBase
{

    /** @var  MaintenanceDrawService */
    private $maintenanceDrawService;

    CONST LIMIT = 10;

    public function initialize()
    {
        $this->checkPermissions();
        parent::initialize();
        $this->maintenanceDrawService = $this->domainAdminServiceFactory->getMaintenanceDrawService();
    }

    public function indexAction()
    {
        $result = $this->maintenanceDrawService->getAllDrawsByLottery('EuroMillions');
        $list_draws_dto = [];
        if($result->success()) {
            /** @var EuroMillionsDraw[] $list_draws */
            $list_draws = $result->getValues();
            foreach($list_draws as $draw){
                $list_draws_dto[] = new DrawDTO($draw);
            }
        }
        $page = (!empty($this->request->getQuery('page'))) ? $this->request->getQuery('page') : 1;
        $paginator = $this->getPaginatorAsArray($list_draws_dto,self::LIMIT,$page);
        /** @var \Phalcon\Mvc\ViewInterface $paginator_view */
        $paginator_view = (new PaginationWidget($paginator, $this->request->getQuery()))->render();

        return $this->view->setVars([
            'draws' => $paginator->getPaginate(),
            'paginator_view' => $paginator_view,
            'page' => $page
        ]);
    }

    public function viewAction()
    {
        $id = $this->request->getPost('id');
        $result = $this->maintenanceDrawService->getDrawById($id);
        $page  = $this->request->getPost('page');
        if($result->success()) {
            echo json_encode(['result'=> 'OK',
                              'value' => $result->getValues(),
                              'page'  => $page
            ]);
        }else{
            echo json_encode(['result'=> 'KO',
                              'value' => $result->errorMessage(),
                              'page'  => $page
            ]);
        }
    }

    public function editAction()
    {
        $this->noRender();
        $id_draw_to_edit = $this->request->getPost('id_draw');
        $numbers = $this->request->getPost('numbers');
        $stars = $this->request->getPost('stars');
        if(!empty($id_draw_to_edit)){

            if(!empty($numbers) && !empty($stars)) {
                $regular_numbers = array_map('intval', explode(',', $numbers));
                $lucky_numbers = array_map('intval', explode(',', $stars));
            } else {
                $regular_numbers = [];
                $lucky_numbers = [];
            }
            $jackpot_value = new Money((int) $this->request->getPost('jackpot')*100, new Currency('EUR'));
            /** @var ActionResult $result */
            $result = $this->maintenanceDrawService->updateLastResult($regular_numbers,$lucky_numbers,$jackpot_value,$id_draw_to_edit);
            $result_draws = $this->maintenanceDrawService->getAllDrawsByLottery('EuroMillions');
            $list_draws_dto = [];
            if($result_draws->success()){
                /** @var EuroMillionsDraw $draw */
                foreach($result_draws->getValues() as $draw) {
                    $list_draws_dto[] = new DrawDTO($draw);
                }
            }
            $page = (!empty($this->request->getPost('page'))) ? $this->request->getPost('page') : 1;
            $paginator = $this->getPaginatorAsArray($list_draws_dto,self::LIMIT,$page);

            if($result->success()){
                echo json_encode(['result' => 'OK',
                                  'value'  => $paginator->getPaginate()->items,
                                  'page'   => $page
                ]);
            } else{
                echo json_encode(['result' => 'KO',
                                  'value'  => $paginator->getPaginate()->items,
                                  'page'   => $page
                ]);
            }
        }
    }

    public function editBreakDownAction()
    {
        $this->noRender();
        $idDraw = $this->request->getPost('id_draw');
        $break_down = $this->request->getPost('breakdown');
        try {
            $this->maintenanceDrawService->storeBreakDown($break_down,$idDraw);
            $page = (!empty($this->request->getPost('page'))) ? $this->request->getPost('page') : 1;
            $result_draws = $this->maintenanceDrawService->getAllDrawsByLottery('EuroMillions');
            $list_draws_dto = [];
            if($result_draws->success()){
                /** @var EuroMillionsDraw $draw */
                foreach($result_draws->getValues() as $draw) {
                    $list_draws_dto[] = new DrawDTO($draw);
                }
                $paginator = $this->getPaginatorAsArray($list_draws_dto,self::LIMIT,$page);
                echo json_encode(['result' => 'OK',
                                  'value'  => $paginator->getPaginate()->items,
                                  'page'   => $page
                ]);
            }
        } catch (\Exception $e) {
            echo json_encode(['result' => 'KO',
                              'value'  => $paginator->getPaginate()->items,
                              'page'   => $page
            ]);
        }
    }

    public function searchAction()
    {
        $this->noRender();
        $date = $this->request->getPost('date');

        if(!empty($date)) {
            try{
                $new_date = new \DateTime($date);
                $result = $this->maintenanceDrawService->getDrawByDate($new_date);
                if($result->success()){
                    /** @var EuroMillionsDraw $draw */
                    $draw = $result->getValues();
                    /** @var DrawDTO $draw_dto */
                    $draw_dto = new DrawDTO($draw);
                    echo json_encode([
                            'result' => 'OK',
                            'value'  => $draw_dto->toArray()
                    ]);
                }else {
                    echo json_encode([
                            'result' => 'KO'
                    ]);
                }
            }catch(\Exception $e) {
                    echo json_encode([
                            'result' => 'KO'
                    ]);
            }
        }else {
            echo json_encode([
                        'result' => 'KO'
            ]);
        }
    }

    private function checkPermissions()
    {
        if (strpos('S', $this->session->get('userAdminAccess'))  !== false) {
            echo 'no entra';
            exit;
        }
    }

}