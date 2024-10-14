<?php

namespace Drupal\promo_code\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Drupal\promo_code\PromoCodeService;

class PromoCodeController extends ControllerBase {
  protected $promoCodeService;

  public function __construct(PromoCodeService $promo_code_service) {
    $this->promoCodeService = $promo_code_service;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('promo_code.promo_code_service')
    );
  }

  // Renderiza el formulario para ingresar el código promocional
  public function promoCodeForm() {
    return [
      '#theme' => 'promo_code_form',
    ];
  }

  // Valida el código ingresado por el usuario
  public function validateCode(Request $request) {
    $code = $request->get('promo_code');
    if (!$code) {
      return new Response('No code provided', 400);
    }

    $valid_code = $this->promoCodeService->loadValidCode($code);
    if (!$valid_code) {
      return new Response('Invalid code', 400);
    }

    // Crear una nueva entidad PromoCode y almacenarla
    $promo_code = $this->promoCodeService->createPromoCode($code);

    return new Response('Code is valid and has been stored', 200);
  }

  // Renderiza el formulario para importar los códigos desde un CSV
  public function importCodesForm() {
    return [
      '#theme' => 'import_codes_form',
    ];
  }

  // Maneja la importación de los códigos desde un archivo CSV
  public function importCodes(Request $request) {
    $file = $request->files->get('csv_file');
    if (!$file || $file->getClientOriginalExtension() !== 'csv') {
      return new Response('Invalid file type', 400);
    }

    $file_path = $file->getRealPath();
    $this->promoCodeService->importCodesFromCsv($file_path);

    return new Response('Códigos cargados exitosamente', 200);
  }
}
