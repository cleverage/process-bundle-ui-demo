<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\ProcessSchedule;
use App\Form\ProcessContextType;
use App\Validator\CronExpression;
use CleverAge\ProcessBundle\Configuration\ProcessConfiguration;
use CleverAge\ProcessBundle\Registry\ProcessConfigurationRegistry;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Process;
use Symfony\Component\Scheduler\Trigger\CronExpressionTrigger;

class ProcessScheduleCrudController extends AbstractCrudController
{
    public function __construct(private readonly ProcessConfigurationRegistry $processConfigurationRegistry)
    {
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setPageTitle('index', 'Scheduler')
            ->showEntityActionsInlined();
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setIcon('fa fa-plus')
                    ->setLabel(false)
                    ->addCssClass('');
            })->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action->setIcon('fa fa-edit')
                    ->setLabel(false)
                    ->addCssClass('text-warning');
            })->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action->setIcon('fa fa-trash-o')
                    ->setLabel(false)
                    ->addCssClass('');
            })->update(Crud::PAGE_INDEX, Action::BATCH_DELETE, function (Action $action) {
                return $action->setLabel('Delete')
                    ->addCssClass('');
            });
    }

    public static function getEntityFqcn(): string
    {
        return ProcessSchedule::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $choices = array_map(function(ProcessConfiguration $configuration) {
            return [$configuration->getCode()];
        }, $this->processConfigurationRegistry->getProcessConfigurations());
        return [
            FormField::addTab('General'),
            TextField::new('process')
                ->setFormType(ChoiceType::class)
                ->setFormTypeOption('choices', array_combine(array_keys($choices), array_keys($choices))),
            TextField::new('cronExpression')
                ->setFormTypeOption('constraints', [new CronExpression()]),
            DateTimeField::new('nextExecution')
                ->setFormTypeOption('mapped', false)
                ->setVirtual(true)
                ->hideOnForm()
                ->hideOnDetail()
                ->formatValue(function ($value, ProcessSchedule $entity) {
                    return CronExpressionTrigger::fromSpec($entity->getCronExpression())->getNextRunDate(new \DateTimeImmutable())->format('c');
                }),
            FormField::addTab('Context'),
            ArrayField::new('context')
                ->setFormTypeOption('entry_type', ProcessContextType::class)
                ->hideOnIndex()
                ->setFormTypeOption('entry_options.label', 'Context (key/value)')
                ->setFormTypeOption('label', '')
                ->setFormTypeOption('required', false)
        ];
    }

    public function index(AdminContext $context): KeyValueStore|RedirectResponse|Response
    {
        if (false === $this->schedulerIsRunning()) {
            $this->addFlash('warning', 'To run scheduler, ensure "bin/console messenger:consume scheduler_cron" console is alive. See https://symfony.com/doc/current/messenger.html#supervisor-configuration.');
        }

        return parent::index($context);
    }

    private function schedulerIsRunning(): bool
    {

        $process = Process::fromShellCommandline('ps -faux');
        $process->run();
        $out = $process->getOutput();

        return str_contains($out, 'scheduler_cron');
    }
}
