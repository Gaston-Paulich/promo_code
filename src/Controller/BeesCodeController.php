<?php

namespace Drupal\bees_code\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\bees_code\BeesCodeService;

class BeesCodeController extends ControllerBase {
  protected $beesCodeService;

  public function __construct(BeesCodeService $bees_code_service) {
    $this->beesCodeService = $bees_code_service;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('bees_code.bees_code_service')
    );
  }

  // Renderiza el formulario para ingresar el código de pdv
  public function beesCodeForm() {
    return [
      '#theme' => 'bees_code_form',
    ];
  }

  // Valida el código ingresado por el usuario
  public function validateCode(Request $request) {
    $code = $request->request->get('bees_code');
    if (!$code) {
      return new JsonResponse(['message' => 'No code provided'], 400);
    }

    try {
      $is_valid = $this->beesCodeService->validateAndMarkCodeAsUsed($code);
      if ($is_valid) {
        return new JsonResponse(['message' => 'Código válido, eres parte de la promoción'], 200);
      } else {
        return new JsonResponse(['message' => 'Código inválido o ya utilizado'], 400);
      }
    } catch (\Exception $e) {
      return new JsonResponse(['message' => $e->getMessage()], 400);
    }
  }
}