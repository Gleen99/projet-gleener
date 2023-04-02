<?php

namespace App\Controller\Admin;

use App\Entity\Comments;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{ AssociationField, IdField, TextField };

class CommentsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string {
        return Comments::class;
    }

    public function configureFields(string $pageName): iterable {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('text')->setLabel('Texte'),
            AssociationField::new('creator')->setLabel('Créateur du commentaire')
        ];
    }

}
