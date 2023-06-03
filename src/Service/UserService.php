<?php

namespace App\Service;

use App\Repository\UserRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class UserService
 */
class UserService {
    protected UserRepository $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param int $page
     * @param int $pageSize
     *
     * @return array
     */
    public function getUsers(int $page, int $pageSize): array
    {
        $userList = [];
        $q = $this->userRepository->findUsers();
        $paginator = new Paginator($q);
        $totalItems = \count($paginator);
        $pagesCount = \intval(\ceil($totalItems / $pageSize));

        $paginator
            ->getQuery()
            ->setFirstResult($pageSize * ($page-1))
            ->setMaxResults($pageSize);

        foreach ($paginator as $pageItem) {
            $userList[] = $pageItem;
        }

        return [$userList, $totalItems, $pagesCount];
    }
}
