<?php

namespace App\Controller\Admin;

use App\Entity\Orders;
use App\Form\OrdersType;
use App\Repository\OrderDetailRepository;
use App\Repository\OrdersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    /**
     * @Route("/admin/orders", name="admin_orders")
     */
    public function orders(OrdersRepository $ordersRepository)
    {
        $orders=$ordersRepository->findAll();
        return $this->render('admin/orders/index.html.twig', [
            'orders' => $orders,
        ]);
    }

    /**
     * @Route("admin/{id}/edit", name="admin_orders_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Orders $order): Response
    {
        $form = $this->createForm(OrdersType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_orders');
        }


        return $this->render('admin/orders/edit.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/{id}/delete", name="admin_orders_delete", methods={"GET","POST"})
     */
    public function delete(Request $request, Orders $orders): Response
    {
        $em=$this->getDoctrine()->getManager();
        $em->remove($orders);
        $em->flush();

        return $this->redirectToRoute('admin_orders');

    }

    /**
     * @Route("admin/{id}", name="admin_orders_show", methods={"GET"})
     */
    public function show(Orders $order,OrderDetailRepository $orderDetailRepository): Response
    {
        $orderid=$order->getId();
        $orderdetail=$orderDetailRepository->findBy(['orderid'=>$orderid]);
        //dump($orderdetail);
        //die();
        return $this->render('admin/orders/show.html.twig', [
            'order' => $order,
            'orderdetail'=>$orderdetail,
        ]);
    }
}
