<?php

namespace Drupal\owlcarousel2;

use Drupal\Core\Render\Markup;
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
   *
   * @throws \Drupal\Core\Entity\EntityMalformedException
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
        if ($item['type'] == 'image') {
          $data            = self::prepareImageCarousel($item, $carousel);
          $content        .= $data['content'];
          $nav_titles[]    = $data['navigation_titles'];
          $nav_image_ratio = $data['nav_ratio'];
        }
        // TODO include navigation images on videos and views.
        elseif ($item['type'] == 'video') {
          $data         = self::prepareVideoCarousel($item, $isTextNavigation);
          $content     .= $data['content'];
          $nav_titles[] = $data['navigation_titles'];
        }
        elseif ($item['type'] == 'view') {
          $data     = self::prepareViewCarousel($item, $isTextNavigation);
          $content .= $data['content'];
          foreach ($data['navigation_titles'] as $item_nav) {
            $nav_titles[] = $item_nav;
          }
        }

      }
    }

    return [
      'content'              => $content,
      'navigation_titles'    => $nav_titles,
      'navigation_img_ratio' => $nav_image_ratio,
    ];

  }

  /**
   * Prepare the image carousel.
   *
   * @param array $item
   *   Item array.
   * @param \Drupal\owlcarousel2\Entity\OwlCarousel2 $carousel
   *   The OwlCarousel object.
   *
   * @return array
   *   content - The image HTML.
   *   navigation_titles - The navigation titles for text navigation.
   *
   * @throws \Drupal\Core\Entity\EntityMalformedException
   */
  private static function prepareImageCarousel(array $item, OwlCarousel2 $carousel) {
    $content            = '';
    $nav_titles         = [];
    $image_nav_ratio    = 0;
    $settings           = $carousel->getSettings();
    $navigation_image   = isset($settings['navigationImage']) && $settings['navigationImage'] == 'true';
    $is_text_navigation = (isset($settings['textNavigation']) && $settings['textNavigation'] == 'true') ? TRUE : FALSE;
    $nav_style_name     = isset($settings['carouselNavigationImageStyle']) ? $settings['carouselNavigationImageStyle'] : 'thumbnail';

    $file       = File::load($item['file_id']);
    $style_name = $item['image_style'];
    $theme      = 'owlcarousel2_image_item';

    $image = [
      '#theme'      => 'image_style',
      '#style_name' => $style_name,
      '#uri'        => ($file instanceof File) ? $file->getFileUri() : '',
    ];

    $node = is_null($item['entity_id']) ? FALSE : Node::load($item['entity_id']);

    // Set navigation title.
    if ($is_text_navigation) {
      if ($navigation_image && isset($item['navigation_image_id']) && isset($item['navigation_image_style']) && $item['navigation_image_id']) {
        $file = File::load($item['navigation_image_id']);
        if (!$file instanceof File) {
          $file = File::load($item['file_id']);
        }
      }

      $image_nav_url = '';
      if ($navigation_image && $file instanceof $file) {
        $style         = \Drupal::entityTypeManager()
          ->getStorage('image_style')
          ->load($nav_style_name);
        $image_nav_url = $style->buildUrl($file->getFileUri());

        $image_nav       = \Drupal::service('image.factory')
          ->get($file->getFileUri());
        $image_nav_ratio = $image_nav->getHeight() / $image_nav->getWidth() * 100;
      }

      $nav_titles = [
        'id'        => $item['id'],
        'title'     => (isset($item['item_label_type']) && $item['item_label_type'] == 'content_title' && $node) ? $node->getTitle() : $item['item_label'] ?: '',
        'image_nav' => $image_nav_url,
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
      $url                = $node->toLink()->getUrl()->toString();
    }
    else {
      $node_render_array = NULL;
      $url               = '';
    }

    $position = [];
    if (isset($item['content_over_image']) && $item['content_over_image'] === 'true') {
      $position['content_vertical_position']   = $item['content_vertical_position'];
      $position['content_horizontal_position'] = $item['content_horizontal_position'];
      $position['content_over_image']          = 'content-over-image';
    }

    $top    = isset($item['content_position_top']) && $item['content_position_top'] ? $item['content_position_top'] . $item['content_position_unit'] : '';
    $right  = isset($item['content_position_right']) && $item['content_position_right'] ? $item['content_position_right'] . $item['content_position_unit'] : '';
    $bottom = isset($item['content_position_bottom']) && $item['content_position_bottom'] ? $item['content_position_bottom'] . $item['content_position_unit'] : '';
    $left   = isset($item['content_position_left']) && $item['content_position_left'] ? $item['content_position_left'] . $item['content_position_unit'] : '';

    $node_render_array['#attributes']['title_color']      = isset($item['title_color']) ? $item['title_color'] : '';
    $node_render_array['#attributes']['content_color']    = isset($item['content_color']) ? $item['content_color'] : '';
    $node_render_array['#attributes']['background_color'] = isset($item['background_color']) ? $item['background_color'] : '';

    $image_item = [
      '#theme'     => $theme,
      'image'      => $image,
      'item_id'    => ['#markup' => $item['id']],
      'url'        => ['#markup' => $url],
      'top'        => ['#markup' => $top],
      'right'      => ['#markup' => $right],
      'bottom'     => ['#markup' => $bottom],
      'left'       => ['#markup' => $left],
      'position'   => $position,
      '#view_mode' => 'carousel',
    ];

    if (isset($item['text_to_display']) && $item['text_to_display'] === 'custom_text') {
      $image_item['node'] = [
        '#theme'      => 'owlcarousel2_custom_text',
        'custom_text' => ['#markup' => Markup::create($item['custom_text'])],
      ];
    }
    else {
      $image_item['node'] = $node_render_array;
    }

    $content .= render($image_item);
    if ($node) {
      // Restore note title.
      $node->setTitle($title);
    }

    return [
      'content'           => $content,
      'navigation_titles' => $nav_titles,
      'nav_ratio'         => $image_nav_ratio,
    ];
  }

  /**
   * Prepare the video carousel.
   *
   * @param array $item
   *   Item array.
   * @param bool $isTextNavigation
   *   If the navigation is text based.
   *
   * @return array
   *   content - The video HTML.
   *   navigation_titles - The navigation titles for text navigation.
   */
  private static function prepareVideoCarousel(array $item, bool $isTextNavigation) {
    $video_url    = $item['video_url'];
    $item_display = '<div id="owlcarousel-video-id-' . $item['id'] . '" 
    class="item-video owl-carousel-video-item" 
    data-hash="' . $item['id'] . '"
    data-youtube-controls="' . $item['youtube_controls'] . '"
    data-youtube-showinfo="' . $item['youtube_showinfo'] . '"
    data-youtube-rel="' . $item['youtube_rel'] . '"
    data-youtube-loop="' . $item['youtube_loop'] . '"
    ><a class="owl-video" href="' . $video_url . '&controls=0"></a></div>';
    $nav_titles   = [];
    $content      = $item_display;

    // Set navigation title.
    if ($isTextNavigation) {
      $nav_titles = [
        'id'    => $item['id'],
        'title' => ($item['item_label_type'] == 'custom_title' && $item['item_label']) ? $item['item_label'] : t('Video'),
      ];
    }

    return [
      'content'           => $content,
      'navigation_titles' => $nav_titles,
    ];
  }

  /**
   * Prepare the video carousel.
   *
   * @param array $item
   *   Item array.
   * @param bool $isTextNavigation
   *   If the navigation is text based.
   *
   * @return array
   *   content - The view HTML.
   *   navigation_titles - The navigation titles for text navigation.
   */
  private static function prepareViewCarousel(array $item, bool $isTextNavigation) {
    $view_id    = explode(':', $item['view_id'])[0];
    $display    = explode(':', $item['view_id'])[1];
    $content    = '';
    $nav_titles = [];

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
            'id'    => 'owl-hash-nid-' . $node->id(),
            'title' => $node->getTitle(),
          ];
        }
      }

    }

    return [
      'content'           => $content,
      'navigation_titles' => $nav_titles,
    ];

  }


  private static function getNavigationInfo(array $item, $navigation_image, $node = NULL) {
    if ($navigation_image && isset($item['navigation_image_id']) && isset($item['navigation_image_style']) && $item['navigation_image_id']) {
      $file = File::load($item['navigation_image_id']);
      if (!$file instanceof File) {
        $file = File::load($item['file_id']);
      }
    }

    $image_nav_url = '';
    if ($navigation_image && $file instanceof $file) {
      $style         = \Drupal::entityTypeManager()
        ->getStorage('image_style')
        ->load($nav_style_name);
      $image_nav_url = $style->buildUrl($file->getFileUri());

      $image_nav       = \Drupal::service('image.factory')
        ->get($file->getFileUri());
      $image_nav_ratio = $image_nav->getHeight() / $image_nav->getWidth() * 100;
    }

    $nav_titles = [
      'id'        => $item['id'],
      'title'     => (isset($item['item_label_type']) && $item['item_label_type'] == 'content_title' && $node) ? $node->getTitle() : $item['item_label'] ?: '',
      'image_nav' => $image_nav_url,
    ];

    return $nav_titles;
  }

  /**
   * Change a file usage creating a link to the new one and remove the old one.
   *
   * @param int $file_id
   *   The file id.
   * @param \Drupal\owlcarousel2\Entity\OwlCarousel2 $carousel
   *   The OwlCarousel.
   * @param int $previous_file_id
   *   The previous file id.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public static function changeFile($file_id, OwlCarousel2 $carousel, $previous_file_id) {
    // Set link file to OwlCarousel2 and set file to permanent.
    $file = File::load($file_id);
    if ($file instanceof File) {
      \Drupal::service('file.usage')
        ->add($file, 'owlcarousel2', $carousel->getEntityTypeId(), $carousel->id());
    }

    // Remove carousel usage from the previous file.
    if ($previous_file_id) {
      $previous_file = $file = File::load($previous_file_id);
      if ($previous_file instanceof File) {
        \Drupal::service('file.usage')
          ->delete($previous_file, 'owlcarousel2', $carousel->getEntityTypeId(), $carousel->id());

        // Delete file if it's not being used anywhere else.
        $usage = \Drupal::service('file.usage')->listUsage($previous_file);
        if (count($usage) == 0) {
          $previous_file->delete();
        }
      }
    }
  }

}
