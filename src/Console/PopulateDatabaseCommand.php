<?php

namespace App\Console;

use Faker\Factory;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Office;
use Illuminate\Support\Facades\Schema;
use Slim\App;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PopulateDatabaseCommand extends Command
{
    private App $app;

    public function __construct(App $app)
    {
        $this->app = $app;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('db:populate');
        $this->setDescription('Populate database');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Populate database...');

        // Créer un nombre aléatoire d'entreprises entre 3 et 5
        for ($i = 0; $i < rand(3, 5); $i++) {
            $company = $this->createCompany();

            // Pour chaque entreprise, créer un nombre aléatoire de bureaux entre 1 et 3
            for ($j = 0; $j < rand(1, 3); $j++) {
                $office = $this->createOffice($company);

                // Si c'est le premier bureau, le définir comme siège social
                if ($j == 0) {
                    $company->head_office_id = $office->id;
                    $company->save();
                }

                // Pour chaque bureau, créer un nombre aléatoire d'employés entre 3 et 7
                for ($k = 0; $k < rand(3, 7); $k++) {
                    $this->createEmployee($office);
                }
            }
        }

        $output->writeln('Database populated successfully!');
        return 0;
    }

    private function createCompany(): Company
    {
        $faker = Factory::create('fr_FR');

        $company = new Company();
        $company->name = $faker->company;
        $company->phone = $faker->phoneNumber;
        $company->email = $faker->companyEmail;
        $company->website = $faker->url;
        $company->image = $faker->imageUrl;
        $company->save();

        return $company;
    }

    private function createEmployee(Office $office): Employee
    {
        $faker = Factory::create('fr_FR');

        $employee = $office->employees()->make();
        $employee->first_name = $faker->firstName;
        $employee->last_name = $faker->lastName;
        $employee->email = $faker->email;
        $employee->phone = $faker->phoneNumber;
        $employee->job_title = $faker->jobTitle;
        $employee->save();

        return $employee;
    }

    private function createOffice(Company $company): Office
    {
        $faker = Factory::create('fr_FR');

        $office = $company->offices()->make();
        $office->name = $faker->company;
        $office->address = $faker->address;
        $office->city = $faker->city;
        $office->zip_code = $faker->postcode;
        $office->country = 'France';
        $office->email = $faker->email;
        $office->phone = $faker->phoneNumber;
        $office->save();

        return $office;
    }
}