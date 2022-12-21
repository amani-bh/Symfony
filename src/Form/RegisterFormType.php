<?php

namespace App\Form;

use App\Entity\User;
use Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType;
use Captcha\Bundle\CaptchaBundle\Validator\Constraints\ValidCaptcha;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RegisterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',EmailType::class)
            ->add('plainPassword',RepeatedType::class,[
                'type'=>PasswordType::class,
                'first_options'=>['label'=>'Password'],
                'second_options' =>['label'=>'Repeated password']
            ])
            #->add('password')
            ->add('firstName',TextType::class)
            ->add('lastName',TextType::class)
            ->add('dateOfBirth',DateType::class, ['widget' => 'single_text'])

            ->add("captchaCode",CaptchaType::class,[
                'captchaConfig' =>'ExampleCaptchaUserRegistration',
                'constraints' =>[
                    new ValidCaptcha([
                        'message' => 'Invalid captcha , please try again'
                    ])
                ]
            ])

            ->add('Register',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
