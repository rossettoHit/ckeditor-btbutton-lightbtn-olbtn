<?php

namespace Drupal\n1ed\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\File\FileSystemInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

use Drupal\n1ed\Flmngr\FlmngrServer\FlmngrServer;

/**
 * Provides route responses for Flmngr file manager.
 */
class FlmngrController extends ControllerBase {

  /**
   * Drupal\Core\File\FileSystemInterface definition.
   *
   * @var \Drupal\Core\File\FileSystemInterface
   */
  protected $FileSystemInterface;

  /**
   * Symfony\Component\HttpFoundation\RequestStack definition.
   *
   * @var Symfony\Component\HttpFoundation\RequestStack
   */
  private $requestStack;

  /**
   * {@inheritdoc}
   */
  public function __construct(FileSystemInterface $file_system,
                              RequestStack $request_stack) {
    $this->FileSystemInterface = $file_system;
    $this->requestStack = $request_stack;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('file_system'),
      $container->get('request_stack')
    );
  }

  /**
   * Calls file manager server side and returns a result to client.
   */
  public function flmngr() {
    
    FlmngrServer::flmngrRequest(
      [
        'dirFiles' => $this->FileSystemInterface->realpath('public://flmngr'),
        'dirTmp' => $this->FileSystemInterface->realpath('public://flmngr-tmp'),
        'dirCache' => $this->FileSystemInterface->realpath('public://flmngr-cache'),
      ],
      $this->requestStack
    );

    return new Response();
  }

}
