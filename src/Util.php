<?php

namespace Drupal\owlcarousel2;

use Drupal\file\Entity\File;
use Drupal\node\Entity\Node;
use Drupal\owlcarousel2\Entity\OwlCarousel2;
use Drupal\views\Views;

/**
 * Class Util.
 *
 * @package Drupal\owlcarousel2
 */
class Util {

  /**
   * Get a list of views to display in a option box.
   *
   * @param string $views_display
   *   The views display plugin if you want to filter by display.
   *
   * @return array
   *   Options array.
   */
  public static function getViewsOptions($views_display = NULL) {

    $views   = Views::getAllViews();
    $options = [];

    foreach ($views as $view) {
      $id              = $view->id();
      $big_description = strlen($view->get('description') > 100) ? '...' : '';
      foreach ($view->get('display') as $display) {
        if (empty($views_display) || $display['display_plugin'] == $views_display) {
          $options[$view->label() . ' : ' .
          substr($view->get('description'), 0, 100) .
          $big_description][$id . ':' . $display['id']] = t('@view : @display_id : @display_title', [
            '@view'          => $view->label(),
            '@display_id'    => $display['id'],
            '@display_title' => $display['display_title'],
          ]);
        }
      }
    }
    ksort($options);

    return $options;
  }

  /**
   * Get the OwlCarousel2 rendered array.
   *
   * @param int $owlcarousel_id
   *   The carousel id.
   *
   * @return array
   *   content - The OwlCarousel HTML.
   *   navigation_titles - The navigation titles for text navigation.
   */
  public static function getCarouselData($owlcarousel_id) {
    $carousel   = OwlCarousel2::load($owlcarousel_id);
    $items      = $carousel->getItems()[0];
    $settings   = $carousel->getSettings();
    $nav_titles = [];

    $isTextNavigation = (isset($settings['textNavigation']) && $settings['textNavigation'] == 'true') ? TRUE : FALSE;
    $content = '';
    if (count($items)) {
      foreach ($items as $item) {
        $item_display = '';
        if ($item['type'] == 'image') {
          $file = File::load($item['file_id']);

          $image = [
            '#theme'      => 'image_style',
            '#style_name' => $item['image_style'],
            '#uri'        => ($file instanceof File) ? $file->getFileUri() : '',
          ];

          $node = is_null($item['entity_id']) ? FALSE : Node::load($item['entity_id']);

          // Set navigation title.
          if ($isTextNavigation) {
            $nav_titles[] = [
              'id' => $item['id'],
              'title' => (isset($item['item_label_type']) && $item['item_label_type'] == 'content_title' && $node) ? $node->getTitle() : $item['item_label'] ?: '',
            ];
          }

          if ($node) {
            // Store title to restore it latter.
            $title             = $node->getTitle();
            $node_render_array = ['#theme' => 'owlcarousel2_node'];
            if (isset($item['display_node_title']) && !$item['display_node_title']) {
              $node->setTitle('');
            }
            $node_render_array += node_view($node, $item['view_mode']);
          }
          else {
            $node_render_array = NULL;
          }

          $position = [];
          if (isset($item['content_over_image']) && $item['content_over_image'] === 'true') {
            $position['content_vertical_position'] = $item['content_vertical_position'];
            $position['content_horizontal_position'] = $item['content_horizontal_position'];
          }

          $url = $node->toLink()->getUrl()->toString();
          $image_item = [
            '#theme'     => 'owlcarousel2_image_item',
            'image'      => $image,
            'node'       => $node_render_array,
            'item_id'    => ['#markup' => $item['id']],
            'url'        => ['#markup' => $url],
            'position'   => $position,
            '#view_mode' => 'carousel',
          ];

          $content .= render($image_item);
          if ($node) {
            // Restore note title.
            $node->setTitle($title);
          }
        }
        elseif ($item['type'] == 'video') {
          $video_url    = $item['video_url'];
          $item_display = '<div class="item-video owl-carousel-video-item" data-hash="' . $item['id'] . '"><a class="owl-video" href="' . $video_url . '"></a></div>';

          $content .= $item_display;

          // Set navigation title.
          if ($isTextNavigation) {
            $nav_titles[] = [
              'id' => $item['id'],
              'title' => ($item['item_label_type'] == 'custom_title' && $item['item_label']) ? $item['item_label'] : t('Video'),
            ];
          }
        }
        elseif ($item['type'] == 'view') {
          $view_id = explode(':', $item['view_id'])[0];
          $display = explode(':', $item['view_id'])[1];

          // Execute view.
          $view = Views::getView($view_id);
          // TODO implement view args (maybe in the future).
          // $view->setArguments($args);
          $view->setDisplay($display);
          $view->preExecute();
          $view->build();

          if ($view->execute()) {
            $view_result = $view->result;
            /** @var \Drupal\views\ResultRow $resultRow */
            foreach ($view_result as $resultRow) {
              /** @var \Drupal\node\Entity\Node $node */
              $node              = $resultRow->_entity;
              $node_render_array = ['#theme' => 'owlcarousel2_node'];

              $node_render_array += node_view($node, $item['view_mode']);
              $content           .= render($node_render_array);

              // Set navigation title.
              if ($isTextNavigation) {
                $nav_titles[] = [
                  'id' => 'owl-hash-nid-' . $node->id(),
                  'title' => $node->getTitle(),
                ];
              }
            }

          }
        }

      }
    }

    return [
      'content' => $content,
      'navigation_titles' => $nav_titles,
    ];

  }

}
