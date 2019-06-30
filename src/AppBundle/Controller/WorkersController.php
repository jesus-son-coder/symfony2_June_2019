<?php


namespace AppBundle\Controller;

use AppBundle\Entity\Workers;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Repository\A0KitRepository;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;




class WorkersController extends Controller
{
    /**
     *@Route("/workers", name="workers_homepage")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Workers');
        $workers = $repo->findAll();

        return $this->render('AppBundle:Workers:index.html.twig', [
            'workers' => $workers
        ]);
    }


    /**
     *@Route("/workers/editable-table", name="workers_editable_table")
     */
    public function editableTableAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Workers');
        $workers = $repo->findAll();

        return $this->render('AppBundle:workers:index-editable-01.html.twig', [
            'workers' => $workers
        ]);
    }

    /**
     *@Route("/workers/editable-data", name="workers_editable_process")
     */
    public function editableProcess(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Workers');
        $workers = $repo->findAll();

        $workersArray = array();
        $i = 0;
        foreach ($workers as $worker) {
            $workersArray[$i]['id'] = $worker->getId();
            $workersArray[$i]['name'] = $worker->getName();
            $workersArray[$i]['company'] = $worker->getCompany();
            $workersArray[$i]['location'] = $worker->getLocation();
            $workersArray[$i]['email'] = $worker->getEmail();
            $workersArray[$i]['telephone'] = $worker->getTelephone();
            $workersArray[$i]['startdate'] = $worker->getStartdate()->format('d-m-Y H:i:s');;
            $i++;
        }

        $data =  json_encode($workersArray);

        return new Response($data);
    }



    /**
     *@Route("/workers/editable-sender", name="workers_editable_sender")
     */
    public function editableUpdater(Request $request)
    {
        $id = $request->request->get('pk');
        $field = $request->request->get('name');
        $value = $request->request->get('value');

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Workers');
        $worker = $repo->find($id);

        $function = "set" . ucfirst($field);

        $worker->$function($value);


        $em->persist($worker);
        $em->flush();

        return new Response(1);
    }


    protected function getData()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Workers');
        $workers = $repo->findAll();

        $workersArray = array();
        $i = 0;
        foreach ($workers as $worker) {
            $workersArray[$i]['id'] = $worker->getId();
            $workersArray[$i]['name'] = $worker->getName();
            $workersArray[$i]['company'] = $worker->getCompany();
            $workersArray[$i]['location'] = $worker->getLocation();
            $workersArray[$i]['email'] = $worker->getEmail();
            $workersArray[$i]['telephone'] = $worker->getTelephone();
            $workersArray[$i]['startdate'] = $worker->getStartdate()->format('d-m-Y H:i:s');;
            $i++;
        }

        return $workersArray;
    }


    /**
     *@Route("/workers/export-excel-sample-01", name="workers_export_excel_sample")
     */
    public function exportToExcelAction()
    {
        $datas = $this->getData();

        $response = $this->get('app.excel_service')->buildExcelFile($datas);
        return $response;
    }
}