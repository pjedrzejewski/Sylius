<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\ReportBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Component\Report\Model\ReportInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;
use Sylius\Bundle\ReportBundle\Form\EventListener\BuildReportRendererFormListener;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * Report form type.
 * 
 * @author Łukasz Chruściel <lchrusciel@gmail.com>
 * @author Mateusz Zalewski <zaleslaw@gmail.com>
 */
class ReportType extends AbstractResourceType
{
    /**
     * Renderer registry
     *
     * @var ServiceRegistryInterface
     */
    protected $rendererRegistry;

    public function __construct($dataClass, array $validationGroups, ServiceRegistryInterface $rendererRegistry)
    {
        parent::__construct($dataClass, $validationGroups);
        
        $this->rendererRegistry = $rendererRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => 'sylius.form.report.name',
                'required' => true
            ))
            ->add('description', 'textarea', array(
                'label'    => 'sylius.form.report.description',
                'required' => false,
            ))
            ->add('renderer', 'sylius_renderer_choice', array(
                'label' => 'sylius.form.report.renderer'
            ))
            ->addEventSubscriber(new BuildReportRendererFormListener($this->rendererRegistry, $builder->getFormFactory()))
        ;

        $prototypes = array();
        $prototypes['renderers'] = array();

        foreach ($this->rendererRegistry->all() as $type => $renderer) {
            $formType = sprintf('sylius_renderer_%s', $renderer->getType());

            if (!$formType) {
                continue;
            }

            try {
                $prototypes['renderers'][$type] = $builder->create('rendererConfiguration', $formType)->getForm();
            } catch (\InvalidArgumentException $e) {
                continue;
            }
        }

        $builder->setAttribute('prototypes', $prototypes);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['prototypes'] = array();

        foreach ($form->getConfig()->getAttribute('prototypes') as $group => $prototypes) {
            foreach ($prototypes as $type => $prototype) {
                $view->vars['prototype'][$group.'_'.$type] = $prototype->createView($view);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sylius_report';
    }
}
