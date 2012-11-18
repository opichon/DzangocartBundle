<?php

namespace Dzangocart\Bundle\DzangocartBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use FOS\UserBundle\Model\UserInterface;

use Dzangocart\Bundle\DzangocartBundle\Form\Type\AccountType;
use Dzangocart\Bundle\DzangocartBundle\Propel\Account;
use Dzangocart\Bundle\DzangocartBundle\Util\Password;

/**
 * @Route("/")
 * @Template
 */
class AccountController extends Controller
{
    /**
     * Signup page.
     *
     * @Route("/signup", name="signup")
     */
    public function signupAction(Request $request)
    {
        $account = new Account();
        $form = $this->createForm(new AccountType(), $account);

        if ($request->isMethod('post')) {
			$form->bindRequest($request);
			if ($form->isValid()) {
                $email = $form->get('email')->getData();
                $userManager = $this->container->get('fos_user.user_manager');

                $user = $userManager->findUserByUsernameOrEmail($email);

                if ($user) {
                    $form->addError(new FormError('signup.email.exists'));
                } else {
                    $password = $this->generatePassword();
                    $user = $this->createUser($email, $password, $account);

                    $data = array('name' => $account->getGivenNames(),
                                  'username' => $email,
                                  'password' => $password);

                    $this->sendMail($email, $data, $request->getLocale());

                    return $this->postSignup($user);
                }
			}
		}

        return $this->render(
            'UAMPracticethaiBundle:Subscription:account.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * Plans page.
     *
     * @Route("/signup/plans", name="signup_plans")
     */
    public function plansAction(Request $request)
    {
        $plans = PlanQuery::create()->getActive($request->getLocale())->find();

        return $this->render(
            'UAMPracticethaiBundle:Subscription:plans.html.twig',
            array('plans' => $plans)
        );
    }

    protected function postSignup(UserInterface $user)
    {
        $this->authenticateUser($user);

        return $this->redirect($this->generateUrl('signup_plans', array()));
    }

    protected function generatePassword()
    {
        $length = $this->container->getParameter('password_length');

        return Password::generate($length);
    }

    protected function createUser($email, $password, $account)
    {
        $manager = $this->container->get('fos_user.user_manager');

        $user = $manager->createUser();
        $user->setUsername($email);
        $user->setEmail($email);
        $user->setPlainPassword($password);
        $user->setAccount($account);
        $user->setEnabled(true);
        $user->setSuperAdmin(false);

        $manager->updateUser($user);

        return $user;
    }

    protected function sendMail($email, $data, $locale = 'en')
    {
        $body = $this->container->get('templating')->render(
            'UAMPracticethaiBundle:Mailer/' . $locale . ':signup.body.html.twig',
            $data
        );

        $subject = $this->container->get('templating')->render(
            'UAMPracticethaiBundle:Mailer/' . $locale . ':signup.subject.txt.twig',
            $data
        );

        $mailer = $this->get('mailer');

        $mail = \Swift_Message::newInstance($subject, $body, 'text/html')->
                    setFrom($this->container->getParameter('signup_sender_email'),
                            $this->container->getParameter('signup_sender_name'))->
                    setTo($email);

        $mailer->send($mail);
    }

    protected function authenticateUser(UserInterface $user)
    {
        $login_manager = $this->container->get('fos_user.security.login_manager');
        $firewall_name = $this->container->getParameter('fos_user.firewall_name');

        try {
            $login_manager->loginUser($firewall_name, $user);
        } catch (\Exception $e) {

        }
    }
}
