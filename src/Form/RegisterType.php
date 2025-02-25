<?php

namespace App\Form;

use App\Entity\User;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'The password field cannot be empty.',
                    ]),
                    new Assert\Length([
                        'min' => 8,
                        'max' => 50,
                        'minMessage' => 'The password must be at least {{ limit }} characters long.',
                        'maxMessage' => 'The password cannot be longer than {{ limit }} characters.',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).*$/',
                        'message' => 'The password must contain at least one uppercase letter, one lowercase letter, and one digit.',
                    ]),
                ],
            ])
            ->add('nom', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'The last name field cannot be empty.',
                    ]),
                    new Assert\Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'The last name must be at least {{ limit }} characters long.',
                        'maxMessage' => 'The last name cannot be longer than {{ limit }} characters.',
                    ]),
                ],
            ])
            ->add('prenom', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'The first name field cannot be empty.',
                    ]),
                    new Assert\Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'The first name must be at least {{ limit }} characters long.',
                        'maxMessage' => 'The first name cannot be longer than {{ limit }} characters.',
                    ]),
                ],
            ])
            ->add('save', SubmitType::class)
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => [new Recaptcha3()],
                'action_name' => 'register/adduser',
                'locale' => 'fr',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
