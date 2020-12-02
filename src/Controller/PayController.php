<?php

namespace App\Controller;

use App\Entity\Billing;
use App\Entity\Pay;
use App\Form\Type\PayType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PayController extends AbstractController
{
    /**
     * Make a payment
     *
     * @Route("/pay/{id}",
     *      name="pay",
     *      requirements={"id"="\d+"},
     *      methods={"GET", "POST"}
     * )
     */
    public function new(Billing $billing, Request $request): Response
    {
        $token = $request->query->get("token");

        if ($token != $billing->getToken()) {
            throw new BadRequestException("The token sent is wrong");
        }

        if ($billing->getStatus()) {
            return $this->render('pay/success.html.twig');
        }

        $pay = new Pay();
        $pay->setBilling($billing);
        $pay->setAmount($billing->getPending());

        $form = $this->createForm(PayType::class, $pay);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Pay $new */
            $new = $form->getData();
            $new->getBilling()
                ->setStatus($new->getAmount() >= $billing->getPending());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($new);
            $entityManager->flush();

            return $this->render('pay/success.html.twig');
        }

        return $this->render(
            'pay/new.html.twig',
            [
                'form' => $form->createView(),
                'billing' => $billing
            ]
        );
    }
}
