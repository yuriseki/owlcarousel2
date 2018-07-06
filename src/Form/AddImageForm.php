<?php

namespace Drupal\owlcarousel2\Form;

use Drupal\Core\Entity\Entity\EntityViewMode;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Drupal\image\Entity\ImageStyle;
use Drupal\node\Entity\Node;
use Drupal\owlcarousel2\Entity\OwlCarousel2;
use Drupal\owlcarousel2\OwlCarousel2Item;

/**
 * Class AddImageForm.
 *
 * @package Drupal\owlcarousel2\Form
 */
class AddImageForm extends AddItemForm {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'owlcarousel2_add_image_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $owlcarousel2 = NULL, $item_id = NULL) {
    $form['#title'] = $this->t('Carousel | Add Image');

    $form_state->set('owlcarousel2', $owlcarousel2);
    $carousel = OwlCarousel2::load($owlcarousel2);

    // Check if it is an edition.
    if ($item_id) {
      $item = $carousel->getItem($item_id);

      $default_file['fids'] = $item['file_id'];

      $form['item_id'] = [
        '#type'  => 'value',
        '#value' => $item_id,
      ];

      $form['weight'] = [
        '#type'  => 'value',
        '#value' => $item['weight'],
      ];
    }

    $form['image'] = [
      '#type'            => 'managed_image',
      '#title'           => $this->t('Image'),
      '#upload_location' => 'public://owlcarousel2',
      '#required'        => TRUE,

      '#default_value' => $item_id ? $default_file : '',

      '#multiple'           => FALSE,
      '#uploda_validators'  => [
        'file_validate_extensions' => ['png, gif, jpg, jpeg'],
      ],
      '#progress_indicator' => 'bar',
      '#progress_message'   => $this->t('Please wait...'),
    ];

    $image_styles_ids = \Drupal::entityQuery('image_style')
      ->execute();

    $image_styles = [];
    foreach ($image_styles_ids as $key => $value) {
      $image_style = ImageStyle::load($value);
      if ($image_style->status() === TRUE) {
        $image_styles[$key] = $image_style->label();
      }
    }

    $form['image_style'] = [
      '#type'          => 'select',
      '#title'         => $this->t('Image style'),
      '#description'   => $this->t('Style to be used on the carousel.'),
      '#options'       => $image_styles,
      '#required'      => TRUE,
      '#empty_option'  => $this->t('Select'),
      '#default_value' => (isset($item['image_style']) && $item['image_style']) ? $item['image_style'] : 'owlcarousel2',
    ];

    $form['entity_id'] = [
      '#type'          => 'entity_autocomplete',
      '#title'         => $this->t('Content to link the carousel item'),
      '#description'   => $this->t('The content to be displayed when the user clicks on the carousel image. Leave empty to not link to anything.'),
      '#default_value' => (isset($item['entity_id'])) ? Node::load($item['entity_id']) : NULL,
      '#required'      => FALSE,
      '#target_type'   => 'node',
    ];

    $form['display_node_title'] = [
      '#type'          => 'checkbox',
      '#title'         => $this->t('Display node title'),
      '#description'   => $this->t('Check if you whant to display the node title on the carousel slide.'),
      '#default_value' => (isset($item['display_node_title']) && $item['display_node_title']) ? $item['display_node_title'] : FALSE,
    ];

    $view_modes_ids = \Drupal::entityQuery('entity_view_mode')
      ->condition('targetEntityType', 'node')
      ->execute();

    $view_modes = [];
    foreach ($view_modes_ids as $value) {
      $key       = substr($value, strlen('node.'), strlen($value));
      $view_mode = EntityViewMode::load($value);
      if ($view_mode->status() === TRUE) {
        $view_modes[$key] = $view_mode->label();
      }
    }

    $form['view_mode'] = [
      '#type'          => 'select',
      '#title'         => $this->t('Node view mode'),
      '#description'   => $this->t('The node view mode to be displayed with the image.'),
      '#options'       => $view_modes,
      '#required'      => FALSE,
      '#empty_option'  => $this->t('Select'),
      '#default_value' => (isset($item['view_mode']) && $item['view_mode']) ? $item['view_mode'] : '',
    ];

    $form['advanced'] = [
      '#type'  => 'details',
      '#title' => $this->t('Advanced configuration'),
    ];

    $form['advanced']['item_label_type'] = [
      '#type'          => 'radios',
      '#title'         => $this->t('Label type'),
      '#description'   => $this->t('If you chose to display text as the navigation links, which label do you want to use?'),
      '#options'       => [
        'content_title' => $this->t('Content title'),
        'custom_title'  => $this->t('Custom title'),
      ],
      '#default_value' => (isset($item['item_label_type']) && $item['item_label_type']) ? $item['item_label_type'] : 'content_title',
    ];

    $form['advanced']['item_label'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Item label'),
      '#description'   => $this->t('Used if you configure the carousel to display text navigation.'),
      '#default_value' => (isset($item['item_label']) && $item['item_label']) ? $item['item_label'] : '',
      '#states'        => [
        'visible' => [':input[name="item_label_type"]' => ['value' => 'custom_title']],
      ],
    ];

    $form['advanced']['content_over_image'] = [
      '#type'          => 'select',
      '#title'         => $this->t('Display content text over image'),
      '#description'   => $this->t('Select "Yes" if you want to display the content linked to the image over the image.'),
      '#options'       => [
        'true'  => $this->t('Yes'),
        'false' => $this->t('No'),
      ],
      '#required'      => TRUE,
      '#empty_option'  => $this->t('Select'),
      '#default_value' => (isset($item['content_over_image']) && $item['content_over_image']) ? $item['content_over_image'] : 'false',
    ];

    $form['advanced']['content_vertical_position'] = [
      '#type'          => 'select',
      '#title'         => $this->t('Content vertical position'),
      '#description'   => $this->t('Vertical position in where the content will be shown over the image.'),
      '#options'       => [
        'vertical-top'   => $this->t('Top'),
        'vertical-center' => $this->t('Center'),
        'vertical-bottom'  => $this->t('Bottom'),
      ],
      '#required'      => TRUE,
      '#empty_option'  => $this->t('Select'),
      '#default_value' => (isset($item['content_vertical_position']) && $item['content_vertical_position']) ? $item['content_vertical_position'] : 'vertical-center',
    ];

    $form['advanced']['content_horizontal_position'] = [
      '#type'          => 'select',
      '#title'         => $this->t('Content horizontal position'),
      '#description'   => $this->t('Horizontal position in where the content will be shown over the image.'),
      '#options'       => [
        'horizontal-left'   => $this->t('Left'),
        'horizontal-center' => $this->t('Center'),
        'horizontal-right'  => $this->t('Right'),
      ],
      '#required'      => TRUE,
      '#empty_option'  => $this->t('Select'),
      '#default_value' => (isset($item['content_horizontal_position']) && $item['content_horizontal_position']) ? $item['content_horizontal_position'] : 'horizontal-center',
    ];

    $form['advanced']['content_padding_unit'] = [
      '#type'          => 'select',
      '#title'         => $this->t('Unit to be used in padding'),
      '#description'   => $this->t('The content can be moved using padding. Select here the unit of measure you want.'),
      '#options'       => [
        '%'  => $this->t('%'),
        'px' => $this->t('Pixels'),
      ],
      '#required'      => TRUE,
      '#empty_option'  => $this->t('Select'),
      '#default_value' => (isset($item['content_padding_unit']) && $item['content_padding_unit']) ? $item['content_padding_unit'] : '%',
    ];

    $form['advanced']['content_padding_top'] = [
      '#type'          => 'number',
      '#title'         => $this->t('Padding top'),
      '#required'      => FALSE,
      '#step'          => .1,
      '#min'           => 0,
      '#max'           => 100,
      '#default_value' => (isset($item['content_padding_top']) && $item['content_padding_top']) ? $item['content_padding_top'] : '',
    ];

    $form['advanced']['content_padding_bottom'] = [
      '#type'          => 'number',
      '#title'         => $this->t('Padding bottom'),
      '#required'      => FALSE,
      '#step'          => .1,
      '#min'           => 0,
      '#max'           => 100,
      '#default_value' => (isset($item['content_padding_bottom']) && $item['content_padding_bottom']) ? $item['content_padding_bottom'] : '',
    ];

    $form['advanced']['content_padding_left'] = [
      '#type'          => 'number',
      '#title'         => $this->t('Padding left'),
      '#required'      => FALSE,
      '#step'          => .1,
      '#min'           => 0,
      '#max'           => 100,
      '#default_value' => (isset($item['content_padding_left']) && $item['content_padding_left']) ? $item['content_padding_left'] : '',
    ];

    $form['advanced']['content_padding_right'] = [
      '#type'          => 'number',
      '#title'         => $this->t('Padding right'),
      '#required'      => FALSE,
      '#step'          => .1,
      '#min'           => 0,
      '#max'           => 100,
      '#default_value' => (isset($item['content_padding_right']) && $item['content_padding_right']) ? $item['content_padding_right'] : '',
    ];

    $form += parent::buildForm($form, $form_state, $owlcarousel2, $item_id);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state, OwlCarousel2 $carousel = NULL) {
    $operation       = $form_state->getValue('operation');
    $owlcarousel2_id = $form_state->getStorage()['owlcarousel2'];
    $file_id         = $form_state->getValue('image')[0];
    $entity_id       = $form_state->getValue('entity_id');
    $carousel        = OwlCarousel2::load($owlcarousel2_id);

    $item_array = [
      'type'                        => 'image',
      'file_id'                     => $file_id,
      'entity_id'                   => $entity_id,
      'view_mode'                   => $form_state->getValue('view_mode'),
      'image_style'                 => $form_state->getValue('image_style'),
      'display_node_title'          => $form_state->getValue('display_node_title'),
      'item_label'                  => $form_state->getValue('item_label'),
      'item_label_type'             => $form_state->getValue('item_label_type'),
      'content_over_image'          => $form_state->getValue('content_over_image'),
      'content_vertical_position'   => $form_state->getValue('content_vertical_position'),
      'content_horizontal_position' => $form_state->getValue('content_horizontal_position'),
      'content_padding_unit'        => $form_state->getValue('content_padding_unit'),
      'content_padding_top'         => $form_state->getValue('content_padding_top'),
      'content_padding_bottom'      => $form_state->getValue('content_padding_bottom'),
      'content_padding_left'        => $form_state->getValue('content_padding_left'),
      'content_padding_right'       => $form_state->getValue('content_padding_right'),
    ];

    if ($operation == 'add') {
      $item = new OwlCarousel2Item($item_array);
      $carousel->addItem($item);

      // Set link file to OwlCarousel2.
      $file = File::load($file_id);
      \Drupal::service('file.usage')
        ->add($file, 'file', $carousel->getEntityTypeId(), $carousel->id());
    }
    else {
      $item_array['id']     = $form_state->getValue('item_id');
      $item_array['weight'] = $form_state->getValue('weight');
      $item                 = new OwlCarousel2Item($item_array);
      $carousel->updateItem($item);
    }

    parent::submitForm($form, $form_state, $carousel);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    if (is_numeric($form_state->getValue('entity_id'))) {
      if ($form_state->getValue('view_mode') == '') {
        $form_state->setErrorByName('view_mode', $this->t('Node view mode is required id you what to display a content.'));
      }
    }

    parent::validateForm($form, $form_state);
  }

}
