<?php 
namespace Spliced\Bundle\ProjectManagerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, array('label' => 'Username','required' => true))
            ->add('email', 'email', array('label' => 'E-Mail Address','required' => true))
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'options' => array(),
                'first_options' => array('label' => 'Password','required' => true),
                'second_options' => array('label' => 'Confirm Password','required' => true),
                'invalid_message' => 'Passwords do not match',
            ))
            ->add('roles', 'bootstrap_checkbox', array('multiple' => true,'required' => true, 'data' => null, 'choices' => $this->getUserRoles()));
        ;
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Spliced\Bundle\ProjectManagerBundle\Entity\User',
        ));
    }

    public function getName()
    {
        return 'user';
    }
    
    protected function getUserRoles(){
    	return array(
    		'ROLE_USER' => 'Basic User',
    		'ROLE_CLIENT' => 'Client',
    		'ROLE_STAFF' => 'Staff',
    		'ROLE_ADMIN' => 'Administrator',
    		'ROLE_SUPER_ADMIN' => 'Super Administrator',
    	);
    }
}
