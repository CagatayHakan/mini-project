<?php

namespace App\Controller;

use App\Entity\OrderDetail;
use App\Entity\Orders;
use App\Form\OrdersType;
use App\Repository\OrderDetailRepository;
use App\Repository\OrdersRepository;
use App\Repository\ShopcardRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/orders")
 */
class OrdersController extends AbstractController
{
    /**
     * @Route("/", name="orders_index", methods={"GET"})
     */
    public function index(OrdersRepository $ordersRepository): Response
    {
        $user=$this->getUser();
        $userid=$user->getId();

        return $this->render('orders/index.html.twig', [
            'orders' => $ordersRepository->findBy(['userid' =>$userid]),
        ]);
    }

    /**
     * @Route("/new", name="orders_new", methods={"GET","POST"})
     */
    public function new(Request $request, ShopcardRepository $shopcardRepository): Response
    {
        $order = new Orders();
        $form = $this->createForm(OrdersType::class, $order);
        $form->handleRequest($request);

        $user=$this->getUser();
        $userid=$user->getId();

        //echo $userid;
        //die();
        $total=$shopcardRepository->getUserShopCardTotal($userid);

        $submittedToken=$request->request->get('_csrf_token');
        if ($this->isCsrfTokenValid('form-order',$submittedToken)) {

            if ($form->isSubmitted()) {
                $entityManager = $this->getDoctrine()->getManager();
                $order->setUserid($userid);
                $order->setAmount($total);
                $order->setStatus("New");
                $entityManager->persist($order);
                $entityManager->flush();

                $orderid=$order->getId();
                $shopcard=$shopcardRepository->getUserShopCard($userid);

                foreach ($shopcard as $item)
                {
                    $orderdetail=new OrderDetail();
                    $orderdetail->setOrderid($orderid);
                    $orderdetail->setUserid($userid);
                    $orderdetail->setProductid($item["productid"]);
                    $orderdetail->setPrice($item["price"]);
                    $orderdetail->setQuantity($item["quantity"]);
                    $orderdetail->setAmount($item["total"]);
                    $orderdetail->setStatus("Ordered");
                    $orderdetail->setName($item["description"]);

                    $entityManager->persist($orderdetail);
                    $entityManager->flush();
                }

                $entityManager=$this->getDoctrine()->getManager();
                $query=$entityManager->createQuery('
                DELETE FROM App\Entity\Shopcard s WHERE s.userid=:userid
                ')->setParameter('userid',$userid);

                $query->execute();
                return $this->redirectToRoute('orders_index');
            }
        }
        return $this->render('orders/new.html.twig', [
            'order' => $order,
            'total'=> $total,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="orders_show", methods={"GET"})
     */
    public function show(Orders $order,OrderDetailRepository $orderDetailRepository): Response
    {
        $orderid=$order->getId();
        $orderdetail=$orderDetailRepository->findBy(['orderid'=>$orderid]);
        //dump($orderdetail);
        //die();
        return $this->render('orders/show.html.twig', [
            'order' => $order,
            'orderdetail'=>$orderdetail,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="orders_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Orders $order): Response
    {
        $form = $this->createForm(OrdersType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('orders_index');
        }


        return $this->render('orders/edit.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="orders_delete", methods={"GET","POST"})
     */
    public function delete(Request $request, Orders $orders): Response
    {
        $em=$this->getDoctrine()->getManager();
        $em->remove($orders);
        $em->flush();

        return $this->redirectToRoute('orders_index');

    }
}
