<?php

namespace Drupal\bcc\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure user settings for this site.
 *
 * @internal
 */
class BccSettingsForm extends ConfigFormBase {

  /**
   * Constructs a \Drupal\user\AccountSettingsForm object.
   *
   * @param ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   *   The role storage.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    parent::__construct($config_factory);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'bcc_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'bcc.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $bcc_config = $this->config('bcc.settings');

    $form['enable'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable'),
      '#description' => $this->t('If checked, all system emails will be sent with the BCC address.'),
      '#return_value' => true,
      '#default_value' => $bcc_config->get('enable'),
    ];

    $form['bcc_mail'] = [
      '#type' => 'email',
      '#title' => $this->t('BCC email address'),
      '#default_value' => $bcc_config->get('bcc_mail'),
      '#description' => $this->t("Enter the e-mail address that should receive copies of all site e-mails."),
      '#maxlength' => 180,
      '#states' => [
        'required' => [
          ':input[name="enable"]' => [
            'checked' => TRUE,
          ],
        ],
        'visible' => [
          ':input[name="enable"]' => [
            'checked' => TRUE,
          ],
        ],
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $this->config('bcc.settings')
      ->set('enable', $form_state->getValue('enable'))
      ->set('bcc_mail', $form_state->getValue('bcc_mail'))
      ->save();
  }

}
