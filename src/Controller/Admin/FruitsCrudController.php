<?php

namespace App\Controller\Admin;

use App\Entity\Fruits;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\Routing\Annotation\Route;

class FruitsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Fruits::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Fruit')
            ->setEntityLabelInSingular('Fruits')
            ->setEntityLabelInSingular(
                fn (?Fruits $fruit, ?string $pageName) => $fruit ? $fruit->toString() : 'Fruit'
            )
            ->setDateFormat('...')
            ->renderContentMaximized()
            ->setPageTitle('index', '%entity_label_plural% listing')
            ->setPageTitle('new', fn () => new \DateTime('now') > new \DateTime('today 13:00') ? 'New dinner' : 'New lunch')
            ->setPageTitle('detail', fn (Fruits $fruit) => (string) $fruit)
//            ->setPageTitle('edit', fn (Category $category) => sprintf('Editing <b>%s</b>', $category->getName()))

            // the help message displayed to end users (it can contain HTML tags)
            ->setHelp('edit', '...')
            ->setPaginatorPageSize(10)
            ;
    }
}
