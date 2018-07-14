<?php

namespace Drupal\owlcarousel2\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\node\Entity\Node;
use Drupal\owlcarousel2\Entity\OwlCarousel2;
use Drupal\views\Entity\View;

/**
 * Form controller for OwlCarousel2 edit forms.
 *
 * @ingroup owlcarousel2
 */
class OwlCarousel2Form extends ContentEntityForm {

  /**
   * The fileStorage.
   *
   * @var \Drupal\file\FileStorageInterface
   */
  protected $fileStorage;

  /**
   * The entityStorage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $entityStorage;

  /**
   * {@inheritdoc}
   */
  public function __construct(EntityManagerInterface $entity_manager) {
    parent::__construct($entity_manager);

    $this->fileStorage = $entity_manager->getStorage('file');
    $this->entityStorage = $entity_manager->getStorage('node');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form = parent::buildForm($form, $form_state);
    /* @var $entity \Drupal\owlcarousel2\Entity\OwlCarousel2 */
    $entity = $this->entity;

    if (!$entity->isNew()) {
      $form['new_revision'] = [
        '#type'          => 'checkbox',
        '#title'         => $this->t('Create new revision'),
        '#default_value' => FALSE,
        '#weight'        => 10,
      ];

      $form['items_container'] = [
        '#type'  => 'fieldset',
        '#title' => $this->t('Items'),
      ];

      $form['items_container']['items'] = $this->formItems($form, $form_state);
    }

    $settings = $entity->getSettings();

    $form['settings'] = [
      '#type' => 'details',
      '#title' => $this->t('Carousel Configuration'),
    ];

    $form['settings']['items_per_slide'] = [
      '#type'          => 'number',
      '#title'         => $this->t('Items per slide'),
      '#description'   => $this->t('How many items per slide?'),
      '#required'      => TRUE,
      '#min'           => 1,
      '#max'           => 10,
      '#default_value' => isset($settings['items_per_slide']) ? $settings['items_per_slide'] : 1,
    ];

    $form['settings']['margin'] = [
      '#type'          => 'number',
      '#title'         => $this->t('Margin'),
      '#description'   => $this->t('Margin between items in px.'),
      '#required'      => TRUE,
      '#min'           => 0,
      '#max'           => 1000,
      '#default_value' => isset($settings['margin']) ? $settings['margin'] : 1,
    ];

    $form['settings']['stagePadding'] = [
      '#type'          => 'number',
      '#title'         => $this->t('Stage Padding'),
      '#description'   => $this->t('Padding left and right on stage (can see neighbours) px.'),
      '#required'      => TRUE,
      '#min'           => 0,
      '#max'           => 1000,
      '#default_value' => isset($settings['stagePadding']) ? $settings['stagePadding'] : 0,
    ];

    $form['settings']['center'] = [
      '#type'          => 'select',
      '#title'         => $this->t('Center item'),
      '#description'   => $this->t('Works well with even an odd number of items. Make sense only if you are displaying more than 1 item per slide.'),
      '#required'      => TRUE,
      '#options'       => [
        'true'  => $this->t('Yes'),
        'false' => $this->t('No'),
      ],
      '#default_value' => isset($settings['center']) ? $settings['center'] : 'true',
    ];

    $form['settings']['loop'] = [
      '#type'          => 'select',
      '#title'         => $this->t('Loop'),
      '#description'   => $this->t('Infinite loop.'),
      '#required'      => TRUE,
      '#options'       => [
        'true'  => $this->t('Yes'),
        'false' => $this->t('No'),
      ],
      '#default_value' => isset($settings['loop']) ? $settings['loop'] : 'true',
    ];

    $form['settings']['autoplay'] = [
      '#type'          => 'select',
      '#title'         => $this->t('Enable auto play'),
      '#description'   => $this->t('If the carousel will play automatically.'),
      '#required'      => TRUE,
      '#options'       => [
        'true'  => $this->t('Yes'),
        'false' => $this->t('No'),
      ],
      '#default_value' => isset($settings['autoplay']) ? $settings['autoplay'] : 'yes',
    ];

    $form['settings']['autoplayHoverPause'] = [
      '#type'          => 'select',
      '#title'         => $this->t('Pause auto play on mouse over'),
      '#description'   => $this->t('If the auto play will pause when mouse over.'),
      '#required'      => TRUE,
      '#options'       => [
        'true'  => $this->t('Yes'),
        'false' => $this->t('No'),
      ],
      '#default_value' => isset($settings['autoplayHoverPause']) ? $settings['autoplayHoverPause'] : 'yes',
    ];

    $form['settings']['autoplaySpeed'] = [
      '#type'          => 'number',
      '#title'         => $this->t('Auto play speed'),
      '#description'   => $this->t('Value em milli-seconds. Only applicable when enable with enable auto play (yes).'),
      '#required'      => FALSE,
      '#min'           => 1,
      '#max'           => 100000,
      '#default_value' => isset($settings['autoplaySpeed']) ? $settings['autoplaySpeed'] : 2000,
    ];

    $form['settings']['autoplayTimeout'] = [
      '#type'          => 'number',
      '#title'         => $this->t('Auto play timeout'),
      '#description'   => $this->t('Value em milli-seconds. Only applicable when enable with enable auto play (yes).'),
      '#required'      => FALSE,
      '#min'           => 1,
      '#max'           => 100000,
      '#default_value' => isset($settings['autoplayTimeout']) ? $settings['autoplayTimeout'] : 5000,
    ];

    $form['settings']['nav'] = [
      '#type'          => 'select',
      '#title'         => $this->t('Show navigation'),
      '#description'   => $this->t('Show the navigation items, the < and > signals'),
      '#required'      => TRUE,
      '#options'       => [
        'true'  => $this->t('Yes'),
        'false' => $this->t('No'),
      ],
      '#default_value' => isset($settings['nav']) ? $settings['nav'] : 'false',
    ];

    $form['settings']['previousText'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Text to PREVIOUS navigation button'),
      '#description'   => $this->t('Text to be used on NEXT navigation button default: "<". HTML allowed.'),
      '#required'      => TRUE,
      '#default_value' => isset($settings['previousText']) ? $settings['previousText'] : '<',
    ];

    $form['settings']['nextText'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Text to NEXT navigation button'),
      '#description'   => $this->t('Text to be used on NEXT navigation button default: ">". HTML allowed.'),
      '#required'      => TRUE,
      '#default_value' => isset($settings['nextText']) ? $settings['nextText'] : '>',
    ];

    $form['settings']['textNavigation'] = [
      '#type'          => 'select',
      '#title'         => $this->t('Show text navigation'),
      '#description'   => $this->t('Show navigation text bellow the images.'),
      '#required'      => TRUE,
      '#options'       => [
        'true'  => $this->t('Yes'),
        'false' => $this->t('No'),
      ],
      '#default_value' => isset($settings['textNavigation']) ? $settings['textNavigation'] : 'false',
    ];

    $form['settings']['navigationAsCarousel'] = [
      '#type'          => 'select',
      '#title'         => $this->t('Use the navigation as the carousel'),
      '#description'   => $this->t('This option will display the navigation as the carousel, and the selected item on above it. In order to work properly, you need to fill navigation text and/or images on each item.'),
      '#required'      => TRUE,
      '#options'       => [
        'true'  => $this->t('Yes'),
        'false' => $this->t('No'),
      ],
      '#default_value' => isset($settings['navigationAsCarousel']) ? $settings['navigationAsCarousel'] : 'false',
    ];

    $form['settings']['dots'] = [
      '#type'          => 'select',
      '#title'         => $this->t('Show dots navigation'),
      '#description'   => $this->t('Show the dots navigation.'),
      '#required'      => TRUE,
      '#options'       => [
        'true'  => $this->t('Yes'),
        'false' => $this->t('No'),
      ],
      '#default_value' => isset($settings['dots']) ? $settings['dots'] : 'true',
    ];

    $form['settings']['dotClass'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Dot Class'),
      '#description'   => $this->t('CSS class to be used on each dot (default: owl-dot).'),
      '#required'      => TRUE,
      '#default_value' => isset($settings['dotClass']) ? $settings['dotClass'] : 'owl-dot',
    ];

    $form['settings']['dotsClass'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Dots Class'),
      '#description'   => $this->t('CSS class to be used wrap the dots (default: owl-dots).'),
      '#required'      => TRUE,
      '#default_value' => isset($settings['dotsClass']) ? $settings['dotsClass'] : 'owl-dots',
    ];

    $form['settings']['lazyLoad'] = [
      '#type'          => 'select',
      '#title'         => $this->t('Lazy load'),
      '#description'   => $this->t('Present images using lazy load. Do not use lazy load if you have videos.'),
      '#required'      => TRUE,
      '#options'       => [
        'true'  => $this->t('Yes'),
        'false' => $this->t('No'),
      ],
      '#default_value' => isset($settings['lazyLoad']) ? $settings['lazyLoad'] : 'false',
    ];

    $effects = ['' => $this->t('Default')];
    $effects += $this->getEffects();

    $form['settings']['animateIn'] = [
      '#type'          => 'select',
      '#title'         => $this->t('Animation when slide goes in'),
      '#description'   => $this->t('The animation to be applied at the item when it goes in. Only works if there is one (1) item per slide.'),
      '#options'       => $effects,
      '#default_value' => isset($settings['animateIn']) ? $settings['animateIn'] : 'false',
    ];

    $form['settings']['animateOut'] = [
      '#type'          => 'select',
      '#title'         => $this->t('Animation when slide goes out'),
      '#description'   => $this->t('The animation to be applied at the item when it goes put. Only works if there is one (1) item per slide.'),
      '#options'       => $effects,
      '#default_value' => isset($settings['animateOut']) ? $settings['animateOut'] : 'false',
    ];

    $form['settings']['mouseDrag'] = [
      '#type'          => 'select',
      '#title'         => $this->t('Mouse drag enabled'),
      '#description'   => $this->t('Make slides mouse draggable.'),
      '#required'      => TRUE,
      '#options'       => [
        'true'  => $this->t('Yes'),
        'false' => $this->t('No'),
      ],
      '#default_value' => isset($settings['mouseDrag']) ? $settings['mouseDrag'] : 'true',
    ];

    $form['settings']['touchDrag'] = [
      '#type'          => 'select',
      '#title'         => $this->t('Touch drag enabled'),
      '#description'   => $this->t('Make slides touch draggable.'),
      '#required'      => TRUE,
      '#options'       => [
        'true'  => $this->t('Yes'),
        'false' => $this->t('No'),
      ],
      '#default_value' => isset($settings['touchDrag']) ? $settings['touchDrag'] : 'true',
    ];

    return $form;
  }

  /**
   * The table with the carousel items.
   */
  public function formItems(array $form, FormStateInterface $form_state) {

    /** @var \Drupal\owlcarousel2\Entity\OwlCarousel2 $entity */
    $entity = $this->entity;
    $items = $entity->getItems();
    uasort($items[0], function ($a, $b) {
      return $a['weight'] < $b['weight'] ? -1 : 1;
    });

    $header = [
      'type'       => $this->t('Type'),
      'weight'     => $this->t('Weight'),
      'id'         => $this->t('Id'),
      'item'       => $this->t('Item'),
      'entity_id'  => $this->t('Entity'),
      'operations' => $this->t('Operations'),
      'item_id'    => '',
    ];

    $table['items'] = [
      '#type'       => 'table',
      '#header'     => $header,
      '#attributes' => ['id' => 'owlcareousel2-items'],
      '#empty'      => $this->t('There are curretly no items in this carousel. Add one by clicking in a button bellow.'),
      '#tabledrag'  => [
        [
          'action'       => 'order',
          'relationship' => 'sibling',
          'group'        => 'items-order-weight',
        ],
      ],
    ];

    if (is_array($items[0])) {
      foreach ($items[0] as $key => $item) {
        $id = $item['id'];

        $image = FALSE;
        if ($item['file_id']) {
          $file = $this->fileStorage->load($item['file_id']);
          $image = [
            '#theme'      => 'image_style',
            '#style_name' => 'thumbnail',
            '#uri'        => ($file instanceof File) ? $file->getFileUri() : '',
          ];
        }

        $node_link = FALSE;
        if ($item['entity_id']) {
          /** @var \Drupal\node\Entity\Node $node */
          $node = $this->entityStorage->load($item['entity_id']);
          if ($node instanceof Node) {
            $node_link = Link::fromTextAndUrl($node->getTitle(), $node->toUrl());
          }
        }

        $weight = [
          '#type'          => 'weight',
          '#title'         => $this->t('Weight for item'),
          '#title_display' => 'invisible',
          '#default_value' => $item['weight'] ? $item['weight'] : $key,
          '#delta'         => 100,
          '#attributes'    => [
            'class' => ['items-order-weight'],
          ],
        ];

        switch ($item['type']) {
          case 'image':
            $type         = $this->t('Image');
            $item_display = render($image);
            $edit_route   = 'owlcarousel2.edit-image';
            break;

          case 'video':
            $type         = $this->t('Video');
            $item_display = Link::fromTextAndUrl($item['video_url'], Url::fromUri($item['video_url']))
              ->toString();
            $edit_route   = 'owlcarousel2.edit-video';
            break;

          case 'view':
            $type         = $this->t('View');
            $view         = View::load(explode(':', $item['view_id'])[0]);
            $url          = $view->toUrl();
            $route        = $url->getRouteName();
            $params       = $url->getRouteParameters();
            $item_display = Link::createFromRoute($item['view_id'], $route, $params)
              ->toString();
            $edit_route   = 'owlcarousel2.edit-view';
            break;

          default:
            $type         = $this->t('Undefined');
            $item_display = $this->t('Undefined');
            $edit_route   = 'owlcarousel2.edit-image';
            break;
        }

        $operations['#type'] = 'operations';

        $operations['#links']['edit'] = [
          'title'  => $this->t('Edit'),
          'url'    => Url::fromRoute($edit_route, [
            'owlcarousel2' => $entity->id(),
            'item_id'      => $item['id'],
          ]),
          'weight' => 0,
        ];

        $operations['#links']['remove'] = [
          'title'  => $this->t('Remove'),
          'url'    => Url::fromRoute('owlcarousel2.remove-item', [
            'owlcarousel2' => $entity->id(),
            'item_id'      => $item['id'],
          ]),
          'weight' => 1,
        ];

        $table['items'][$key] = [
          '#attributes' => [
            'class' => 'draggable',
            'id'    => $id,
          ],
          '#weight'     => NULL,
          'type'        => ['#markup' => $type],
          'weight'      => $weight,
          'id'          => ['#markup' => $item['id']],
          'item'        => [
            '#tree' => FALSE,
            'label' => ['#markup' => $item_display],
          ],
          'description' => ['#markup' => $node_link ? $node_link->toString() : ''],
          'operations'  => $operations,
          'item_id'     => [
            '#type'  => 'value',
            '#value' => $item['id'],
          ],
        ];
      }
    }

    $add_image = Link::createFromRoute($this->t('Add Image'), 'owlcarousel2.add-image', [
      'owlcarousel2' => $entity->id(),
      'method'       => 'nojs',
    ], ['attributes' => ['class' => ['button']]]);

    $add_video = Link::createFromRoute($this->t('Add Video'), 'owlcarousel2.add-video', [
      'owlcarousel2' => $entity->id(),
      'method'       => 'nojs',
    ], ['attributes' => ['class' => ['button']]]);

    $add_view = Link::createFromRoute($this->t('Add View'), 'owlcarousel2.add-view', [
      'owlcarousel2' => $entity->id(),
      'method'       => 'nojs',
    ], ['attributes' => ['class' => ['button']]]);

    $table['add'][] = [
      'data' => [
        'add' => [
          '#type'   => 'markup',
          '#markup' => $add_image->toString() . $add_video->toString() . $add_view->toString(),
          '#prefix' => '<div id="owlcareousel2-add_button">',
          '#suffix' => '</div>',
        ],
      ],
    ];

    return $table;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    /** @var \Drupal\owlcarousel2\Entity\OwlCarousel2 $entity */
    $entity = $this->entity;

    // Save as a new revision if requested to do so.
    if (!$form_state->isValueEmpty('new_revision') && $form_state->getValue('new_revision') != FALSE) {
      $entity->setNewRevision();

      // If a new revision is created, save the current user as revision author.
      $entity->setRevisionCreationTime(REQUEST_TIME);
      $entity->setRevisionUserId($this->currentUser()->id());
    }
    else {
      $entity->setNewRevision(FALSE);
    }

    // Adjust items weight.
    if (!$entity->isNew()) {
      $saved_entity = OwlCarousel2::load($entity->id());
      $items        = $saved_entity->getItems();
      $items_form   = $form_state->getValue('items');
      foreach ($items[0] as $key => $item) {
        foreach ($items_form as $key_form => $item_form) {
          if ($item_form['item_id'] == $item['id']) {
            $items[0][$key]['weight'] = $items_form[$key_form]['weight'];
            break;
          }
        }
      }

      // Reorder items according to weight.
      uasort($items[0], function ($a, $b) {
        return $a['weight'] < $b['weight'] ? -1 : 1;
      });

      $entity->set('items', $items);
    }

    // Save settings.
    $settings_keys     = $form['settings'];
    $form_state_values = $form_state->getValues();
    $settings          = [];
    foreach ($settings_keys as $key => $value) {
      if (isset($form_state_values[$key])) {
        $settings[$key] = $form_state->getValue($key);
      }
    }
    $entity->set('settings', $settings);

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label OwlCarousel2.', [
          '%label' => $entity->label(),
        ]));
        $route_name = 'entity.owlcarousel2.edit_form';
        break;

      default:
        drupal_set_message($this->t('Saved the %label OwlCarousel2.', [
          '%label' => $entity->label(),
        ]));
        $route_name = 'entity.owlcarousel2.canonical';
    }
    $form_state->setRedirect($route_name, ['owlcarousel2' => $entity->id()]);
  }

  /**
   * The available affects.
   *
   * @return array
   *   Array with the effects.
   */
  private function getEffects() {
    return [
      'bounce'             => $this->t('bounce'),
      'bounceIn'           => $this->t('bounceIn'),
      'bounceInDown'       => $this->t('bounceInDown'),
      'bounceInLeft'       => $this->t('bounceInLeft'),
      'bounceInRight'      => $this->t('bounceInRight'),
      'bounceInUp'         => $this->t('bounceInUp'),
      'bounceOut'          => $this->t('bounceOut'),
      'bounceOutDown'      => $this->t('bounceOutDown'),
      'bounceOutLeft'      => $this->t('bounceOutLeft'),
      'bounceOutRight'     => $this->t('bounceOutRight'),
      'bounceOutUp'        => $this->t('bounceOutUp'),
      'fadeIn'             => $this->t('fadeIn'),
      'fadeInDown'         => $this->t('fadeInDown'),
      'fadeInDownBig'      => $this->t('fadeInDownBig'),
      'fadeInLeft'         => $this->t('fadeInLeft'),
      'fadeInLeftBig'      => $this->t('fadeInLeftBig'),
      'fadeInRight'        => $this->t('fadeInRight'),
      'fadeInRightBig'     => $this->t('fadeInRightBig'),
      'fadeInUp'           => $this->t('fadeInUp'),
      'fadeInUpBig'        => $this->t('fadeInUpBig'),
      'fadeOut'            => $this->t('fadeOut'),
      'fadeOutDown'        => $this->t('fadeOutDown'),
      'fadeOutDownBig'     => $this->t('fadeOutDownBig'),
      'fadeOutLeft'        => $this->t('fadeOutLeft'),
      'fadeOutLeftBig'     => $this->t('fadeOutLeftBig'),
      'fadeOutRight'       => $this->t('fadeOutRight'),
      'fadeOutRightBig'    => $this->t('fadeOutRightBig'),
      'fadeOutUp'          => $this->t('fadeOutUp'),
      'fadeOutUpBig'       => $this->t('fadeOutUpBig'),
      'flash'              => $this->t('flash'),
      'flipInX'            => $this->t('flipInX'),
      'flipInY'            => $this->t('flipInY'),
      'flipOutX'           => $this->t('flipOutX'),
      'flipOutY'           => $this->t('flipOutY'),
      'headShake'          => $this->t('headShake'),
      'hinge'              => $this->t('hinge'),
      'jackInTheBox'       => $this->t('jackInTheBox'),
      'jello'              => $this->t('jello'),
      'lightSpeedIn'       => $this->t('lightSpeedIn'),
      'lightSpeedOut'      => $this->t('lightSpeedOut'),
      'pulse'              => $this->t('pulse'),
      'rollIn'             => $this->t('rollIn'),
      'rollOut'            => $this->t('rollOut'),
      'rotateIn'           => $this->t('rotateIn'),
      'rotateInDownLeft'   => $this->t('rotateInDownLeft'),
      'rotateInDownRight'  => $this->t('rotateInDownRight'),
      'rotateInUpLeft'     => $this->t('rotateInUpLeft'),
      'rotateInUpRight'    => $this->t('rotateInUpRight'),
      'rotateOut'          => $this->t('rotateOut'),
      'rotateOutDownLeft'  => $this->t('rotateOutDownLeft'),
      'rotateOutDownRight' => $this->t('rotateOutDownRight'),
      'rotateOutUpLeft'    => $this->t('rotateOutUpLeft'),
      'rotateOutUpRight'   => $this->t('rotateOutUpRight'),
      'rubberBand'         => $this->t('rubberBand'),
      'shake'              => $this->t('shake'),
      'slideInDown'        => $this->t('slideInDown'),
      'slideInLeft'        => $this->t('slideInLeft'),
      'slideInRight'       => $this->t('slideInRight'),
      'slideInUp'          => $this->t('slideInUp'),
      'slideOutDown'       => $this->t('slideOutDown'),
      'slideOutLeft'       => $this->t('slideOutLeft'),
      'slideOutRight'      => $this->t('slideOutRight'),
      'slideOutUp'         => $this->t('slideOutUp'),
      'swing'              => $this->t('swing'),
      'tada'               => $this->t('tada'),
      'wobble'             => $this->t('wobble'),
      'zoomIn'             => $this->t('zoomIn'),
      'zoomInDown'         => $this->t('zoomInDown'),
      'zoomInLeft'         => $this->t('zoomInLeft'),
      'zoomInRight'        => $this->t('zoomInRight'),
      'zoomInUp'           => $this->t('zoomInUp'),
      'zoomOut'            => $this->t('zoomOut'),
      'zoomOutDown'        => $this->t('zoomOutDown'),
      'zoomOutLeft'        => $this->t('zoomOutLeft'),
      'zoomOutRight'       => $this->t('zoomOutRight'),
      'zoomOutUp'          => $this->t('zoomOutUp'),

    ];
  }

}
