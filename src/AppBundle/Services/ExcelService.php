<?php


namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Response;
use \PDO;

class ExcelService
{
    protected $container;
    protected $em;
    protected $cellWidth=30;

    public function __construct(EntityManager $em , Container $container)
    {
        $this->em = $em;
        $this->container=$container;
    }


    public function buildExcelFile($data)
    {
        // Create an empty object: :
        $phpExcelObject = $this->container->get('phpexcel')->createPHPExcelObject();

        // Ou on peut créer un Objet vide à partir d'un fichier Excel existant :
        // $phpExcelObjectFromFile = $this->container->get('phpexcel')->createPHPExcelObject('file.xls');

        $sheet = $phpExcelObject->setActiveSheetIndex(0);
        $this->setSpecificCellWidth($sheet,'A', 'G');

        $sheet->setCellValue('A1', 'Id')
            ->setCellValue('B1', 'Name')
            ->setCellValue('C1', 'Company')
            ->setCellValue('D1', 'Location')
            ->setCellValue('E1', 'Email')
            ->setCellValue('F1', 'Telephone')
            ->setCellValue('G1', 'StartDate');


        foreach ($data as $key => $row) {
            $lineIndex = $key + 2;
            $sheet->setCellValue('A' . $lineIndex, $row['id'])
                ->setCellValue('B' . $lineIndex, $row['name'])
                ->setCellValue('C' . $lineIndex, $row['company'])
                ->setCellValue('D' . $lineIndex, $row['location'])
                ->setCellValue('E' . $lineIndex, $row['email'])
                ->setCellValue('F' . $lineIndex, $row['telephone'])
                ->setCellValue('G' . $lineIndex, $row['startdate']);
        }

        // $today = (new \DateTime())->format('Y-m-d H:i:s');
        $today = date('d-m-Y');
        $onglet = 'Liting du ' . $today;

        $phpExcelObject->getActiveSheet()->setTitle($onglet);


        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);


        $writer = $this->container->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
        $response = $this->container->get('phpexcel')->createStreamedResponse($writer);


        // Adding headers
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'contactList' . '.xlsx'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);
        return $response;
    }



    /* Appliquer une taille de largeur uniforme à toutes les colonnes comprises entre la colonne de départ et la colonne de fin : */
    private function setCellWidth($sheet,$columnReferenceStart,$columnReferenceEnd){
        $acceptedValue = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 'A', 'B', 'C', 'D' ,'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S'];
        if( (! in_array($columnReferenceStart, $acceptedValue) && ! in_array($columnReferenceEnd, $acceptedValue))
        || ord($columnReferenceStart) > ord($columnReferenceEnd)) return $sheet;

        $startIndex = ord($columnReferenceStart);
        $endIndex = ord($columnReferenceEnd);

        for($i = $startIndex; $i <= $endIndex; $i++) {
            $letter = chr($i);

            $sheet->getColumnDimension($letter)->setWidth($this->cellWidth);
            $sheet->getStyle($letter)->getAlignment()->setWrapText(true);
            $sheet->getStyle($letter)->getAlignment()->setVertical('top');
        }

        return $sheet;
    }

    /* Appliquer une taille de largeur spécifique à chaque colonne ! */
    private function setSpecificCellWidth($sheet,$columnReferenceStart,$columnReferenceEnd){
        $acceptedValue = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 'A', 'B', 'C', 'D' ,'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S'];
        if( (! in_array($columnReferenceStart, $acceptedValue) && ! in_array($columnReferenceEnd, $acceptedValue))
            || ord($columnReferenceStart) > ord($columnReferenceEnd)) return $sheet;

        $startIndex = ord($columnReferenceStart);
        $endIndex = ord($columnReferenceEnd);

        for($i = $startIndex; $i <= $endIndex; $i++) {
            $letter = chr($i);
            if($i == 65) {
                $sheet->getColumnDimension($letter)->setWidth(3.43);
            }
            elseif ($i == 66) {
                $sheet->getColumnDimension($letter)->setWidth(21);
            }
            elseif ($i == 67) {
                $sheet->getColumnDimension($letter)->setWidth(32);
            }
            elseif ($i == 68) {
                $sheet->getColumnDimension($letter)->setWidth(22);
            }
            elseif ($i == 69) {
                $sheet->getColumnDimension($letter)->setWidth(41);
            }
            elseif ($i == 70) {
                $sheet->getColumnDimension($letter)->setWidth(14);
            }
            elseif ($i == 71) {
                $sheet->getColumnDimension($letter)->setWidth(19);
            }

            $sheet->getStyle($letter)->getAlignment()->setWrapText(true);
            $sheet->getStyle($letter)->getAlignment()->setVertical('top');

        }

        return $sheet;
    }
}