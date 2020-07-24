<?php


namespace Mittwald\Web2pdf\Middleware;


use Mittwald\Web2pdf\View\PdfView;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PdfHandler implements MiddlewareInterface
{
    /**
     * @var PdfView
     */
    private $pdfView;

    public function __construct(PdfView $pdfView)
    {
        $this->pdfView = $pdfView;
    }

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $printPage = $request->getParsedBody()['printPage'] ?? $request->getQueryParams()['printPage'] ?? null;


        if ($printPage === null) {
            return $handler->handle($request);
        }

        ob_clean();

        $response = $handler->handle($request);

        return $this->pdfView->renderHtmlOutput($response->getBody(), 'TEst');
    }
}