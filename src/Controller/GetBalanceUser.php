<?php

namespace App\Controller;

use App\Entity\Billing;
use App\Entity\Pay;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetBalanceUser
{
    protected $security;

    /**
     * Construction function
     *
     * @param Security $security Service Security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * Get User Balance
     *
     * @Route(
     *     name="api_users_balance",
     *     path="/balance",
     *     methods={"GET"}
     * )
     *
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        /**
         * User
         *
         * @var User $user
         */
        $user  = $this->security->getUser();
        $total = 0;

        if ($user) {
            foreach ($user->getBillings() as $billing) {
                if ($billing instanceof Billing) {
                    foreach ($billing->getPays() as $pay) {
                        if ($pay instanceof Pay) {
                            $total = $total+$pay->getAmount();
                        }
                    }
                }
            }
        }

        return new JsonResponse(
            [
                "total" => round($total, 2)
            ]
        );
    }
}