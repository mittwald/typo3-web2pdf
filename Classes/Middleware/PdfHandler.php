<?php


namespace Mittwald\Web2pdf\Middleware;


use Mittwald\Web2pdf\Options\ModuleOptions;
use Mittwald\Web2pdf\View\PdfView;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class PdfHandler implements MiddlewareInterface
{
    /**
     * @var PdfView
     */
    private $pdfView;
    /**
     * @var TypoScriptFrontendController
     */
    private $frontendController;
    /**
     * @var ModuleOptions
     */
    private $moduleOptions;

    /**
     * PdfHandler constructor.
     * @param PdfView $pdfView
     * @param ModuleOptions $moduleOptions
     */
    public function __construct(PdfView $pdfView, ModuleOptions $moduleOptions)
    {
        $this->pdfView = $pdfView;
        $this->frontendController = TypoScriptFrontendController::getGlobalInstance();
        $this->moduleOptions = $moduleOptions;
    }

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $pluginParams = $request->getQueryParams()['tx_web2pdf_pi1'] ?? null;

        if ($pluginParams === null) {
            return $handler->handle($request);
        }

        if (($pluginParams['argument'] !== ModuleOptions::QUERY_PARAMETER)) {
            return $handler->handle($request);
        }

        $output = $handler->handle($request);

        ob_clean();

        $response = new Response();
        $file = $this->pdfView->renderHtmlOutput($output->getBody(), $this->frontendController->generatePageTitle());
        $destination = ($pdfDestination = $this->moduleOptions->getPdfDestination()) ? $pdfDestination : 'attachment';

        $response = $response->withHeader('Content-Transfer-Encoding', 'binary');
        $response->getBody()->write(file_get_contents($file));
        $response = $response->withHeader('Content-Type', 'application/pdf');
        $response = $response->withHeader('Content-Disposition', $destination . '; filename="' . basename($file) . '"');

        return $response->withStatus(200);
    }
}