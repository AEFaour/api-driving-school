<?php

namespace App\DataFixtures;

use App\Entity\Course;
use App\Entity\Instructor;
use App\Entity\Invoice;
use App\Entity\Learner;
use App\Entity\Result;
use App\Entity\Salary;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    /**
     * AppFixtures constructor.
     * @param $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($u = 0; $u < 15; $u++) {
            $user = new User();

            $hash = $this->encoder->encodePassword($user, "password");

            $chrono = 1;

            $user->setFirstName($faker->firstName())
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setPassword($hash);
            $manager->persist($user);

            for ($l = 0; $l < 50; $l++) {
                $learner = new Learner();
                $learner->setFirstName($faker->firstName())
                    ->setLastName($faker->lastName)
                    ->setEmail($faker->email)
                    ->setTelephone($faker->phoneNumber)
                    ->setJob($faker->jobTitle)
                    ->setUser($user);
                $manager->persist($learner);
                for ($i = 0; $i < mt_rand(3, 10); $i++) {
                    $invoice = new Invoice();
                    $invoice->setAmount($faker->randomFloat(2, 500, 7500))
                        ->setSentAt($faker->dateTimeBetween('-6 months'))
                        ->setStatus($faker->randomElement(['SENT', 'PAID', 'CANCELLED']))
                        ->setLearner($learner)
                        ->setChrono($chrono);

                    $chrono++;

                    $manager->persist($invoice);
                }
                for ($r = 0; $r < mt_rand(1, 5); $r++) {
                    $result = new Result();
                    $result->setStatus($faker->randomElement(['ADMITTED', 'ADJOURNED']))
                        ->setObtainedAt($faker->dateTime)
                        ->setLearner($learner);
                    $manager->persist($result);
                }
                for ($c = 0; $c < mt_rand(1, 5); $c++) {
                    $course = new Course();
                    $course->setCategory($faker->randomElement(['YOUNG', 'INTENSIVE', 'EXTENSIVE']))
                        ->setDuration($faker->randomElement(['LONG', 'SHORT']))
                        ->setPrice($faker->randomFloat(2, 500, 1250))
                        ->addLearner($learner);
                    $manager->persist($course);
                }
                for ($t = 0; $t < mt_rand(1, 10); $t++) {
                    $instructor = new Instructor();
                    $instructor->setFirstName($faker->firstName())
                        ->setLastName($faker->lastName)
                        ->setEmail($faker->email)
                        ->setTelephone($faker->phoneNumber)
                        ->setCourse($course);
                    $manager->persist($instructor);
                    for ($s = 0; $s < 15; $s++) {
                        $salary = new Salary();
                        $salary->setAmount($faker->randomFloat(2, 1800, 2500))
                            ->setPaidAt($faker->dateTimeBetween('-6 months'))
                            ->setInstructor($instructor);
                        $manager->persist($salary);
                    }
                }
            }
        }
        $manager->flush();
    }
}
