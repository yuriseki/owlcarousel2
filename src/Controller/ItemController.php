<?php

namespace Drupal\owlcarousel2\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\owlcarousel2\Entity\OwlCarousel2;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class ItemController.
 *
 * @package Drupal\owlcarousel2\Controller
 */
class ItemController extends ControllerBase {

  /**
   * The fileStorage.
   *
   * @var \Drupal\file\FileStorageInterface
   */
  protected $fileStorage;

  /**
   * The fileUsage.
   *
   * @var \Drupal\file\FileUsage\FileUsageInterface
   */
  protected $fileUsage;

  /**
   * {@inheritdoc}
   */
  public function __construct(ContainerInterface $container) {
    $this->fileStorage = $container->get('entity_type.manager')->getStorage('file');
    $this->fileUsage = $container->get('file.usage');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static ($container);
  }

  /**
   * Revmove one item from OwlCarousel2.
   *
   * @param int $owlcarousel2
   *   The OwlCarousel2 id.
   * @param string $item_id
   *   The item id.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   *   The redirect response.
   */
  public function remove($owlcarousel2, $item_id) {
    $carousel = OwlCarousel2::load($owlcarousel2);
    $items    = $carousel->getItems();
    foreach ($items[0] as $key => $value) {
      if ($value['id'] == $item_id) {
        unset($items[0][$key]);

        // Remove carousel usage from the file.
        if (isset($value['file_id'])) {
          $file = $this->fileStorage->load($value['file_id']);
          $this->fileUsage->delete($file, 'owlcarousel2', $carousel->getEntityTypeId(), $carousel->id());

          $usage = $this->fileUsage->listUsage($file);
          if (count($usage) == 0) {
            $file->delete();
          }
        }
        break;
      }
    }

    $carousel->set('items', $items);
    $carousel->save();

    $url = new Url('entity.owlcarousel2.edit_form', [
      'owlcarousel2' => $owlcarousel2,
    ]);

    return new RedirectResponse($url->toString());
  }

}
