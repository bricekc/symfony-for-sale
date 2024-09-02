<?php

namespace App\Controller;

use App\Entity\Advertisement;
use App\Form\AdvertisementType;
use App\Repository\AdvertisementRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdvertisementController extends AbstractController
{
    #[Route('/advertisement', name: 'app_advertisement')]
    public function index(AdvertisementRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $search = $request->query->get('search', '');
        $all = $repository->queryAllByDateWithCategory($search);
        $all = $paginator->paginate(
            $all,
            $request->query->getInt('page', 1),
            15
        );

        return $this->render('advertisement/index.html.twig', [
            'advertisements' => $all,
            'title' => $search ? 'Résultat de la recherche' : 'Toutes les annonces',
        ]);
    }

    #[Route('/advertisement/{id<\d+>}', name: 'app_advertisement_show')]
    public function show(#[MapEntity(expr: 'repository.queryAdvertisementWithHisCategory(id)')] Advertisement $advertisement): Response
    {
        return $this->render('advertisement/show.html.twig', [
            'advertisement' => $advertisement,
        ]);
    }

    #[Route('/advertisement/new', name: 'app_advertisement_new')]
    public function new(Request $request, AdvertisementRepository $repository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        $add = new Advertisement();
        $form = $this->createForm(AdvertisementType::class, $add);
        $form->add('new', SubmitType::class, ['label' => 'Ajouter', 'attr' => ['class' => 'btn btn-primary']]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($add, true);

            return $this->redirectToRoute('app_advertisement_show', ['id' => $add->getId()]);
        }

        return $this->render('advertisement/_form.html.twig', ['form' => $form, 'advertisement' => $add]);
    }

    #[Route('/advertisement/{id}/update', name: 'app_advertisement_update', requirements: ['id' => '\d+'])]
    public function update(Request $request, ManagerRegistry $doctrine, #[MapEntity(expr: 'repository.queryAdvertisementWithHisCategory(id)')] Advertisement $add): Response
    {
        $this->denyAccessUnlessGranted('EDIT_DELETE_ADVERTISEMENT', $add);
        $entityManager = $doctrine->getManager();
        $form = $this->createForm(AdvertisementType::class, $add);
        $form->add('new', SubmitType::class, ['label' => 'Modifier', 'attr' => ['class' => 'btn btn-primary']]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_advertisement_show', ['id' => $add->getId()]);
        }

        return $this->render('advertisement/_form.html.twig', ['advertisement' => $add, 'form' => $form]);
    }
    #[Route('/advertisement/{id}/delete', name: 'app_advertisement_delete', requirements: ['id' => '\d+'])]
    public function delete(Advertisement $advertisement, AdvertisementRepository $repository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('EDIT_DELETE_ADVERTISEMENT', $advertisement);
        $form = $this->createFormBuilder($advertisement)
            ->add('delete', SubmitType::class, ['label' => 'Supprimer', 'attr' => ['class' => 'btn btn-danger']])
            ->add('cancel', SubmitType::class, ['label' => 'Annuler', 'attr' => ['class' => 'btn btn-primary']])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $submittedToken = $request->request->get('token');
            if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
                $repository->remove($advertisement, true);
            }
            return $this->redirectToRoute('app_advertisement');
        } elseif ($form->isSubmitted() && $form->getClickedButton() !== $form->get('delete')) {
            return $this->redirectToRoute('app_advertisement_show', ['id' => $advertisement->getId()]);
        }
        return $this->render('advertisement/_delete.html.twig', ['form' => $form, 'advertisement' => $advertisement]);
    }
}
