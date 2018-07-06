<?php

namespace Drupal\owlcarousel2;

/**
 * Class OwlCarousel2Item.
 *
 * @package Drupal\owlcarousel2
 */
class OwlCarousel2Item {

  /**
   * The item id, represented by UUID.
   *
   * @var string
   */
  private $id;

  /**
   * The image file id.
   *
   * @var int
   */
  private $fileId;

  /**
   * The entity id to be presented when user clicks at the banner.
   *
   * @var int
   */
  private $entityId;

  /**
   * The youtube or vimeo video url.
   *
   * @var string
   */
  private $videoUrl;

  /**
   * The item type.
   *
   *  - custom: for custom item.
   *  - view: for view item.
   *
   * @var string
   */
  private $type;

  /**
   * The view id.
   *
   * @var string
   */
  private $viewId;

  /**
   * The item weight.
   *
   * @var int
   */
  private $weight;

  /**
   * The node view mode.
   *
   * @var string
   */
  private $viewMode;

  /**
   * The image style.
   *
   * @var string
   */
  private $imageStyle;

  /**
   * Display or not the node title.
   *
   * @var bool
   */
  private $displayNodeTitle;

  /**
   * The label to be displayed on navigation.
   *
   * Used if the carousel is configured to display text navigation.
   *
   * @var string
   */
  private $itemLabel;

  /**
   * Item label type.
   *
   * If the carousel is configured to display navigation bullets as text, which
   * type text should be presented: The content title or a custom one?
   *
   * @var string
   */
  private $itemLabelType;

  /**
   * If the node content will be displayed over the image.
   *
   * @var bool
   */
  private $contentOverImage;

  /**
   * The node content vertical position.
   *
   * @var string
   */
  private $contentVerticalPosition;

  /**
   * The node content horizontal position.
   *
   * @var string
   */
  private $contentHorizontalPosition;

  /**
   * The node content position unit.
   *
   * @var string
   */
  private $contentPositionUnit;

  /**
   * The node content position top.
   *
   * @var double
   */
  private $contentPositionTop;

  /**
   * The node content position bottom.
   *
   * @var double
   */
  private $contentPositionBottom;

  /**
   * The node content position left.
   *
   * @var double
   */
  private $contentPositionLeft;

  /**
   * The node content position right.
   *
   * @var double
   */
  private $contentPositionRight;

  /**
   * Get the contentOverImage value.
   *
   * @return bool
   *   The contentOverImage value.
   */
  public function isContentOverImage() {
    return $this->contentOverImage;
  }

  /**
   * Set the contentOverImage value.
   *
   * @param bool $contentOverImage
   *   The contentOverImage.
   */
  public function setContentOverImage($contentOverImage) {
    $this->contentOverImage = $contentOverImage;
  }

  /**
   * Get the contentVerticalPosition value.
   *
   * @return string
   *   The contentVerticalPosition value.
   */
  public function getContentVerticalPosition() {
    return $this->contentVerticalPosition;
  }

  /**
   * Set the contentVerticalPosition value.
   *
   * @param string $contentVerticalPosition
   *   The contentVerticalPosition.
   */
  public function setContentVerticalPosition($contentVerticalPosition) {
    $this->contentVerticalPosition = $contentVerticalPosition;
  }

  /**
   * Get the contentHorizontalPosition value.
   *
   * @return string
   *   The contentHorizontalPosition value.
   */
  public function getContentHorizontalPosition() {
    return $this->contentHorizontalPosition;
  }

  /**
   * Set the contentHorizontalPosition value.
   *
   * @param string $contentHorizontalPosition
   *   The contentHorizontalPosition.
   */
  public function setContentHorizontalPosition($contentHorizontalPosition) {
    $this->contentHorizontalPosition = $contentHorizontalPosition;
  }

  /**
   * Get the contentPositionUnit value.
   *
   * @return string
   *   The contentPositionUnit value.
   */
  public function getContentPositionUnit() {
    return $this->contentPositionUnit;
  }

  /**
   * Set the contentPositionUnit value.
   *
   * @param string $contentPositionUnit
   *   The contentPositionUnit.
   */
  public function setContentPositionUnit($contentPositionUnit) {
    $this->contentPositionUnit = $contentPositionUnit;
  }

  /**
   * Get the contentPositionTop value.
   *
   * @return float
   *   The contentPositionTop value.
   */
  public function getContentPositionTop() {
    return $this->contentPositionTop;
  }

  /**
   * Set the contentPositionTop value.
   *
   * @param float $contentPositionTop
   *   The contentPositionTop.
   */
  public function setContentPositionTop($contentPositionTop) {
    $this->contentPositionTop = $contentPositionTop;
  }

  /**
   * Get the contentPositionBottom value.
   *
   * @return float
   *   The contentPositionBottom value.
   */
  public function getContentPositionBottom() {
    return $this->contentPositionBottom;
  }

  /**
   * Set the contentPositionBottom value.
   *
   * @param float $contentPositionBottom
   *   The contentPositionBottom.
   */
  public function setContentPositionBottom($contentPositionBottom) {
    $this->contentPositionBottom = $contentPositionBottom;
  }

  /**
   * Get the contentPositionLeft value.
   *
   * @return float
   *   The contentPositionLeft value.
   */
  public function getContentPositionLeft() {
    return $this->contentPositionLeft;
  }

  /**
   * Set the contentPositionLeft value.
   *
   * @param float $contentPositionLeft
   *   The contentPositionLeft.
   */
  public function setContentPositionLeft($contentPositionLeft) {
    $this->contentPositionLeft = $contentPositionLeft;
  }

  /**
   * Get the contentPositionRight value.
   *
   * @return float
   *   The contentPositionRight value.
   */
  public function getContentPositionRight() {
    return $this->contentPositionRight;
  }

  /**
   * Set the contentPositionRight value.
   *
   * @param float $contentPositionRight
   *   The contentPositionRight.
   */
  public function setContentPositionRight($contentPositionRight) {
    $this->contentPositionRight = $contentPositionRight;
  }

  /**
   * Get the itemLabelType value.
   *
   * @return string
   *   The itemLabelType value.
   */
  public function getItemLabelType() {
    return $this->itemLabelType;
  }

  /**
   * Set the itemLabelType value.
   *
   * @param string $itemLabelType
   *   The itemLabelType.
   */
  public function setItemLabelType($itemLabelType) {
    $this->itemLabelType = $itemLabelType;
  }

  /**
   * Get the itemLabel value.
   *
   * @return string
   *   The itemLabel value.
   */
  public function getItemLabel() {
    return $this->itemLabel;
  }

  /**
   * Set the itemLabel value.
   *
   * @param string $itemLabel
   *   The itemLabel.
   */
  public function setItemLabel($itemLabel) {
    $this->itemLabel = $itemLabel;
  }

  /**
   * Get the image_style value.
   *
   * @return string
   *   The image_style value.
   */
  public function getImageStyle() {
    return $this->imageStyle;
  }

  /**
   * Set the image_style value.
   *
   * @param string $imageStyle
   *   The image_style.
   */
  public function setImageStyle($imageStyle) {
    $this->imageStyle = $imageStyle;
  }

  /**
   * Get the display_node_title value.
   *
   * @return bool
   *   The display_node_title value.
   */
  public function isDisplayNodeTitle() {
    return $this->displayNodeTitle;
  }

  /**
   * Set the display_node_title value.
   *
   * @param bool $displayNodeTitle
   *   The display_node_title.
   */
  public function setDisplayNodeTitle($displayNodeTitle) {
    $this->displayNodeTitle = $displayNodeTitle;
  }

  /**
   * Get the view_mode value.
   *
   * @return string
   *   The view_mode value.
   */
  public function getViewMode() {
    return $this->viewMode;
  }

  /**
   * Set the view_mode value.
   *
   * @param string $viewMode
   *   The view_mode.
   */
  public function setViewMode($viewMode) {
    $this->viewMode = $viewMode;
  }

  /**
   * Get the weight value.
   *
   * @return int
   *   The weight value.
   */
  public function getWeight() {
    return $this->weight;
  }

  /**
   * Set the weight value.
   *
   * @param int $weight
   *   The weight.
   */
  public function setWeight($weight) {
    $this->weight = $weight;
  }

  /**
   * Get the id value.
   *
   * @return string
   *   The id value.
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Get the file_id value.
   *
   * @return int
   *   The file_id value.
   */
  public function getFileId() {
    return $this->fileId;
  }

  /**
   * Set the file_id value.
   *
   * @param int $fileId
   *   The file_id.
   */
  public function setFileId($fileId) {
    $this->fileId = $fileId;
  }

  /**
   * Get the entity_id value.
   *
   * @return int
   *   The entity_id value.
   */
  public function getEntityId() {
    return $this->entityId;
  }

  /**
   * Set the entity_id value.
   *
   * @param int $entityId
   *   The entity_id.
   */
  public function setEntityId($entityId) {
    $this->entityId = $entityId;
  }

  /**
   * Get the video_url value.
   *
   * @return string
   *   The video_url value.
   */
  public function getVideoUrl() {
    return $this->videoUrl;
  }

  /**
   * Set the video_url value.
   *
   * @param string $videoUrl
   *   The video_url.
   */
  public function setVideoUrl($videoUrl) {
    $this->videoUrl = $videoUrl;
  }

  /**
   * Get the type value.
   *
   * @return string
   *   The type value.
   */
  public function getType() {
    return $this->type;
  }

  /**
   * Set the type value.
   *
   * @param string $type
   *   The type.
   */
  public function setType($type) {
    $this->type = $type;
  }

  /**
   * Get the view_id value.
   *
   * @return string
   *   The view_id value.
   */
  public function getViewId() {
    return $this->viewId;
  }

  /**
   * Set the view_id value.
   *
   * @param string $viewId
   *   The view_id.
   */
  public function setViewId($viewId) {
    $this->viewId = $viewId;
  }

  /**
   * The array representation of the item.
   *
   * @return array
   *   The array representation.
   */
  public function getArray() {
    return [
      'id'                          => $this->getId(),
      'type'                        => $this->getType(),
      'file_id'                     => $this->getFileId(),
      'entity_id'                   => $this->getEntityId(),
      'video_url'                   => $this->getVideoUrl(),
      'view_id'                     => $this->getViewId(),
      'weight'                      => $this->getWeight(),
      'view_mode'                   => $this->getViewMode(),
      'image_style'                 => $this->getImageStyle(),
      'display_node_title'          => $this->isDisplayNodeTitle(),
      'item_label'                  => $this->getItemLabel(),
      'item_label_type'             => $this->getItemLabelType(),
      'content_over_image'          => $this->isContentOverImage(),
      'content_vertical_position'   => $this->getContentVerticalPosition(),
      'content_horizontal_position' => $this->getContentHorizontalPosition(),
      'content_position_unit'        => $this->getContentPositionUnit(),
      'content_position_top'         => $this->getContentPositionTop(),
      'content_position_bottom'      => $this->getContentPositionBottom(),
      'content_position_left'        => $this->getContentPositionLeft(),
      'content_position_right'       => $this->getContentPositionRight(),
    ];
  }

  /**
   * OwlCarousel2Item constructor.
   *
   * @param array $item_array
   *   The array representation of the item.
   */
  public function __construct(array $item_array) {
    $this->setType(isset($item_array['type']) ? $item_array['type'] : NULL);
    $this->setFileId(isset($item_array['file_id']) ? $item_array['file_id'] : NULL);
    $this->setEntityId(isset($item_array['entity_id']) ? $item_array['entity_id'] : NULL);
    $this->setVideoUrl(isset($item_array['video_url']) ? $item_array['video_url'] : NULL);
    $this->setViewId(isset($item_array['view_id']) ? $item_array['view_id'] : NULL);
    $this->setWeight(isset($item_array['weight']) ? $item_array['weight'] : NULL);
    $this->setViewMode(isset($item_array['view_mode']) ? $item_array['view_mode'] : NULL);
    $this->setImageStyle(isset($item_array['image_style']) ? $item_array['image_style'] : NULL);
    $this->setDisplayNodeTitle(isset($item_array['display_node_title']) ? $item_array['display_node_title'] : FALSE);
    $this->setItemLabel(isset($item_array['item_label']) ? $item_array['item_label'] : NULL);
    $this->setItemLabelType(isset($item_array['item_label_type']) ? $item_array['item_label_type'] : 'content_title');
    $this->setContentOverImage(isset($item_array['content_over_image']) ? $item_array['content_over_image'] : FALSE);
    $this->setContentVerticalPosition(isset($item_array['content_vertical_position']) ? $item_array['content_vertical_position'] : '');
    $this->setContentHorizontalPosition(isset($item_array['content_horizontal_position']) ? $item_array['content_horizontal_position'] : '');
    $this->setContentPositionUnit(isset($item_array['content_position_unit']) ? $item_array['content_position_unit'] : '');
    $this->setContentPositionTop(isset($item_array['content_position_top']) ? $item_array['content_position_top'] : '');
    $this->setContentPositionBottom(isset($item_array['content_position_bottom']) ? $item_array['content_position_bottom'] : '');
    $this->setContentPositionLeft(isset($item_array['content_position_left']) ? $item_array['content_position_left'] : '');
    $this->setContentPositionRight(isset($item_array['content_position_right']) ? $item_array['content_position_right'] : '');

    $id = isset($item_array['id']) ? $item_array['id'] : NULL;

    if (is_null($id)) {
      $uuid_service = \Drupal::service('uuid');
      $this->id     = $uuid_service->generate();
    }
    else {
      $this->id = $id;
    }
  }

}
