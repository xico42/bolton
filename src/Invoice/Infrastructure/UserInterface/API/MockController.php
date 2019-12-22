<?php

declare(strict_types=1);

namespace Bolton\Invoice\Infrastructure\UserInterface\API;

use Bolton\Invoice\Infrastructure\InvoiceSource\ArquiveiAPI\Mock\MockInvoiceXmlProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Paginator\Paginator;
use Zend\Paginator\ScrollingStyle\Sliding;
use Swagger\Annotations as SWG;

class MockController
{
    /**
     * Fakes Arquivei API
     *
     * This is mainly used by the system and integration tests
     *
     * @SWG\Tag(name="Mock")
     *
     * @Route("/api/mock/invoices", methods={"GET"})
     *
     * @SWG\Response(
     *     response="200",
     *     description="Response identical to Arquivei's API"
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function invoices(Request $request)
    {
        $currentPage = $request->query->get('cursor', 1);
        $data = $this->getInvoices();

        $paginator = new Paginator(new ArrayAdapter($data));
        $paginator->setCurrentPageNumber($currentPage);
        $paginator->setItemCountPerPage(2);
        $paginator->setCacheEnabled(false);
        $pages = $paginator->getPages(new Sliding());

        $currentItems = iterator_to_array($paginator->getCurrentItems());
        if ($currentPage > $pages->pageCount) {
            $currentItems = [];
        }

        return new JsonResponse([
            'status' => [
                'code' => 200,
                'message' => 'OK'
            ],
            'data' => $currentItems,
            'page' => [
                'next' => 'http://web/api/mock/invoices?cursor=' . ($pages->next ?? $pages->last + 1),
                'previous' => 'http://web/api/mock/invoices?cursor=' . ($pages->previous ?? 0),
            ],
            'count' => count($currentItems),
            'signature' => 'foobar',
        ]);
    }

    private function getInvoices(): array
    {
        return (new MockInvoiceXmlProvider())
            ->getAll();
    }
}
