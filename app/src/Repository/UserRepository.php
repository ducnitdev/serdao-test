<?php 

namespace App\Repository;

use Doctrine\DBAL\Connection;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @param Connection $connection
     * @param ManagerRegistry $registry
     */
    public function __construct(Connection $connection, ManagerRegistry $registry)
    {
        $this->connection = $connection;
        parent::__construct($registry, User::class);
    }

    /**
     * @return bool
     * @throws \Doctrine\DBAL\Exception
     */
    public function tableExists(): bool
    {
        $sql = "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = 'symfony' AND table_name = 'user';";
        return (bool) $this->connection->fetchOne($sql);
    }

    /**
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    public function createUserTable(): void
    {
        $sql = "CREATE TABLE user (id INT AUTO_INCREMENT PRIMARY KEY, first_name VARCHAR(255), last_name VARCHAR(255), address VARCHAR(255))";
        $this->connection->executeStatement($sql);
    }

    /**
     * @return void
     */
    public function seedUsers(): void
    {
        $users = [
            new User(),
            new User(),
            new User(),
        ];

        $users[0]->setFirstName('Barack');
        $users[0]->setLastName('Obama');
        $users[0]->setAddress('White House');

        $users[1]->setFirstName('Britney');
        $users[1]->setLastName('Spears');
        $users[1]->setAddress('America');

        $users[2]->setFirstName('Leonardo');
        $users[2]->setLastName('DiCaprio');
        $users[2]->setAddress('Titanic');

        $em = $this->getEntityManager();
        foreach ($users as $user) { 
            $em->persist($user);
        }
        $em->flush();
    }

    /**
     * @param User $user
     * @return void
     */
    public function save(User $user): void
    {
        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();
    }

    /**
     * @param User $user
     * @return void
     */
    public function remove(User $user): void
    {
        $em = $this->getEntityManager();
        $em->remove($user);
        $em->flush();
    }
}
