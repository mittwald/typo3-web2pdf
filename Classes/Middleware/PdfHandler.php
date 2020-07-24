<?php


namespace Mittwald\Web2pdf\Middleware;


use Mittwald\Web2pdf\View\PdfView;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Http\Stream;

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

        $output = $handler->handle($request);

        ob_clean();

        $response = new Response();
        $file = $this->pdfView->renderHtmlOutput($output->getBody(), 'TEst');

        $response = $response->withHeader('Content-Transfer-Encoding', 'binary');
        $response->getBody()->write(file_get_contents($file));
        $response = $response->withHeader('Content-Type', 'application/pdf');
        $response = $response->withHeader('Content-Disposition', $file . '; filename="test.pdf"');

        return $response->withStatus(200);
    }
}