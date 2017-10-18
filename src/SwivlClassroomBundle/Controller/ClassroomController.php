<?php

namespace SwivlClassroomBundle\Controller;


use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use SwivlClassroomBundle\Entity\Classroom;


class ClassroomController extends FOSRestController
{
    public function getAllAction()
    {
        $classrooms = $this->getDoctrine()->getRepository('SwivlClassroomBundle:Classroom')->findAll();
        if ($classrooms === null) {
            return new View("there are no classrooms exist", Response::HTTP_NOT_FOUND);
        }
        return $classrooms;
    }

    public function getOneAction($id)
    {
        $classroom = $this->getDoctrine()->getRepository('SwivlClassroomBundle:Classroom')->find($id);
        if ($classroom === null) {
            return new View("classroom not found", Response::HTTP_NOT_FOUND);
        }
        return $classroom;
    }

    public function addAction(Request $request)
    {
        $classroom = new Classroom();
        $name = $request->get('name');
        $active = $request->get('active');
        $date = new \DateTime("now");

        if(empty($name) || empty($active))
        {
            return new View("Null values are not allowed", Response::HTTP_NOT_ACCEPTABLE);
        }
        $classroom->setName($name);
        $classroom->setActive($active);
        $classroom->setDateCreation($date);

        $em = $this->getDoctrine()->getManager()->persist($classroom);
        $em->flush();
        return new View("Classroom added successfully", Response::HTTP_OK);
    }


    public function updateAction(Request $request)
    {
        $name = $request->get('name');
        $active = $request->get('active');
        $id = $request->get('id');

        if(empty($name) && empty($active) ||  empty($id)) {
            return new View("Data cannot be empty", Response::HTTP_NOT_ACCEPTABLE);
        }

        $data = compact('name','active');

        $sn = $this->getDoctrine()->getManager();
        $classroom = $this->getDoctrine()->getRepository('SwivlClassroomBundle:Classroom')->find($id);

        if (empty($classroom)) {
            return new View("Classroom not found", Response::HTTP_NOT_FOUND);
        }

        foreach ($data as $key => $value){
            if ($value){
                $method = 'set'.ucfirst($key);
                $classroom->$method($value);
            }
        }

        $sn->flush();
        return new View("Classroom are updated successfully", Response::HTTP_OK);
    }


    public function deleteAction(Request $request)
    {
        $id = $request->get('id');
        $sn = $this->getDoctrine()->getManager();
        $classroom = $this->getDoctrine()->getRepository('SwivlClassroomBundle:Classroom')->find($id);
        if (empty($classroom)) {
            return new View("Classroom not found", Response::HTTP_NOT_FOUND);
        }
        else {
            $sn->remove($classroom);
            $sn->flush();
        }
        return new View("Deleted successfully", Response::HTTP_OK);
    }
}
