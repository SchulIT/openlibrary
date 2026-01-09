<?php

namespace App\Repository;

use App\Entity\User;

class UserRepository extends AbstractRepository implements UserRepositoryInterface {
    public function persist(User $user): void {
        $this->em->persist($user);
        $this->em->flush();
    }

    public function remove(User $user): void {
        $this->em->remove($user);
        $this->em->flush();
    }
}