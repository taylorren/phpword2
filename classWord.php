<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of classWord
 *
 * @author tr
 */

namespace trWord
{

    use PhpOffice\PhpWord\Autoloader;
    use PhpOffice\PhpWord\Settings;
    use PhpOffice\PhpWord\IOFactory;
    use Symfony\Component\HttpFoundation\Request as Request;

    class Word
    {

        public function index($app)
        {
            return $app['twig']->render('index.html.twig');
        }
        
        private function getInput(Request $req)
        {
            $keys=['fullname', 'gender', 'age', 'message'];
            $info=[];
            foreach ($keys as $key)
            {
                $info[$key]=$req->get($key);
            }
            
            return $info;
        }

        private function openDoc()
        {
            return \PhpOffice\PhpWord\IOFactory::load(__DIR__ . "/template/output.docx");
                
        }
        public function export($app, Request $req)
        {
            //Grab the POST data
            $info=$this->getInput($req);
            
            //require_once __DIR__ . '/PHPWord/src/PhpWord/Autoloader.php';
            Autoloader::register();
            Settings::loadConfig();

            $template = new \PhpOffice\PhpWord\TemplateProcessor(__DIR__ . "/template/template.docx");
            
            $this->setContent($template, $info);
            
            $template->saveAs(__DIR__ . "/template/output.docx");
            
            $wadoc=$this->openDoc();
            
            $this->setHeader($wadoc, $info);
            
            $fn="/template/output2.docx";
            $wadoc->save(__DIR__.$fn, 'Word2007');
            
            return $app['twig']->render('done.html.twig', array('link'=>$fn));
        }

        private function setContent(\PhpOffice\PhpWord\TemplateProcessor $doc, $info)
        {
            $gender = $info['gender'];
            if($gender=='male')
            {
                $salute='Mr.';
            }
            else
            {
                $salute='Ms.';
            }
            
            $doc->setValue('salute', $salute);
            $doc->setValue('fullname', $info['fullname']);
            $doc->setValue('age', $info['age']);
            $doc->setValue('message', $info['message']);
            $doc->setValue('printdate', date('Y-m-d h:i:s'));
        }

        private function setHeader(\PhpOffice\PhpWord\PhpWord $wa, $info)
        {
            $wa->getDocInfo()
                    ->setCreator('Taylor Ren')
                    ->setTitle('Welcome Letter')
                    ->setSubject('A greeting letter generated with PHP lib to ' . $info['fullname'])
                    ->setKeywords("php; word; certificate; {$info['fullname']}")
            ;
        }

    }

}