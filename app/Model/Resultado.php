<?php
/**
 * Created by PhpStorm.
 * User: LockonDaniel
 * Date: 9/7/14
 * Time: 20:59
 */

class Resultado extends AppModel{
    public $useTable = "examenResultado";
    public $belongsTo = array(
        'Evaluacion'=>
        array(
            "className"=>'Evaluacion',
            'foreignKey'=>'examen_id'
        ),
    );

    public $findMethods = array('motor' =>  true);



    protected function _findMotor($state, $query, $results = array())
    {
        if ($state === 'before') {
            $query['joins']= array(
              array(
                  'table'=>'preguntas',
                  'alias'=>'Pregunta',
                  'type'=>'INNER',
                  'conditions'=>array('Resultado.pregunta_id = Pregunta.id')
              ),
              array(
                  'table'=>'temas',
                  'alias'=>'Tema',
                  'type'=>'INNER',
                  'conditions'=>array('Pregunta.tema_id = Tema.id')
              ),
              array(
                  'table'=>'materias',
                  'alias'=>'Materia',
                  'type'=>'INNER',
                  'conditions'=>array('Tema.materia_id = Materia.id')
              ),
            );
            $query['fields']= array('Pregunta.id','Resultado.correcta','Pregunta.dificultad','Pregunta.tema_id','Materia.id');
            $query['order']= array('Materia.id','Tema.id');
            return $query;
        }
        array_walk($results, function(&$value, $key){
           $value['id']=$value['Pregunta']['id'];
           $value['dificultad']=$value['Pregunta']['dificultad'];
           $value['correcta']=$value['Resultado']['correcta'];
           $value['tema']=$value['Pregunta']['tema_id'];
           $value['materia']=$value['Materia']['id'];
            unset($value['Pregunta'],$value['Resultado'],$value['Materia']);
        });
        return $results;
    }

    /**
     *
     */
    public function createResult($pregunta,$correcta,$examen,$seleccionada)
    {
        $data = array(
            'pregunta_id'=>$pregunta,
            'correcta'=>$correcta,
            'examen_id'=>$examen,
            'seleccionada'=>$seleccionada,
        );
        $this->create();
        if($this->save($data))
            return true;
        return false;
    }





} 