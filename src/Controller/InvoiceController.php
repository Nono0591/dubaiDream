<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

final class InvoiceController extends AbstractController
{
    private function getLogoBase64(): ?string
    {
        $logoFile = $this->getParameter('kernel.project_dir') . '/public/uploads/logoInverse.png';

        if (!file_exists($logoFile)) {
            return null;
        }

        $logoData = base64_encode(file_get_contents($logoFile));

        return 'data:image/png;base64,' . $logoData;
    }

    #[Route('/compte/facture/impression/{id_order}', name: 'app_invoice_customer')]
    public function printForCustomer(OrderRepository $orderRepository, int $id_order): Response
    {
        $order = $orderRepository->find($id_order);

        if (!$order || $order->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('app_account');
        }

        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);

        $html = $this->renderView('invoice/index.html.twig', [
            'order' => $order,
            'logoBase64' => $this->getLogoBase64(),
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="facture-'.$order->getId().'.pdf"',
            ]
        );
    }

    #[Route('/admin/facture/impression/{id_order}', name: 'app_invoice_admin')]
    public function printForAdmin(OrderRepository $orderRepository, int $id_order): Response
    {
        $order = $orderRepository->find($id_order);

        if (!$order) {
            return $this->redirectToRoute('admin');
        }

        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);

        $html = $this->renderView('invoice/index.html.twig', [
            'order' => $order,
            'logoBase64' => $this->getLogoBase64(),
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="facture-'.$order->getId().'.pdf"',
            ]
        );
    }
}