<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Message;
use App\Entity\User;
use App\Helper\TimeSinceCreationTrait;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MessageController
 * @package App\Controller
 */
class MessageController extends AbstractController
{
    use TimeSinceCreationTrait;

    /**
     * @var MessageRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * MessageController constructor.
     *
     * @param MessageRepository      $repository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(MessageRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/ajax/message/create", name="message.ajax.create", methods={"POST"})
     *
     * @param Request $request
     *
     * @return bool|JsonResponse
     */
    public function ajaxCreate(Request $request): JsonResponse
    {
        $isAjax = $request->isXmlHttpRequest();
        if ($isAjax) {
            $data = $request->request->all();

            $figure = $this->entityManager->getRepository(Figure::class)->findOneBy(['id' => $data['figure_id']]);
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $this->getUser()->getId()]);

            $message = (new Message())
                ->setFigure($figure)
                ->setUser($user)
                ->setContent($data['content'])
            ;

            $this->entityManager->persist($message);
            $this->entityManager->flush();

            return new JsonResponse([
                'message' => [
                    'id' => $message->getId(),
                    'user' => [
                        'username' => $message->getUser()->getUsername(),
                        'avatar' => $message->getUser()->getAvatar()
                    ]
                ]
            ]);
        }
        return false;
    }

    /**
     * @Route("/ajax/message/loadmore", name="message.ajax.loadmore", methods={"POST"})
     *
     * @param Request $request
     *
     * @return bool|JsonResponse
     */
    public function ajaxLoadMore(Request $request): JsonResponse
    {
        $isAjax = $request->isXmlHttpRequest();
        if ($isAjax) {
            $data = $request->request->all();
            $conversation = $this->entityManager->getRepository(Message::class)->findBy(['figure' => $data['figure_id']], ['created_at' => 'DESC'], $data['limit'], $data['offset']);

            $limit = intval($data['limit']) + 10;
            $offset = intval($data['offset']) + count($conversation);
            $anotherOne = $this->entityManager->getRepository(Message::class)->findBy(['figure' => $data['figure_id']], ['created_at' => 'DESC'], $limit, $offset);

            if (!empty($conversation)) {
                foreach ($conversation as $key => $message) {
                    $messages[$key]['id'] = $message->getId();
                    $messages[$key]['content'] = $message->getContent();
                    $messages[$key]['date'] = $this->dateSinceCreation($message->getCreatedAt()->format('Y-m-d H:i:s'));
                    $messages[$key]['user']['username'] = $message->getUser()->getUsername();
                    $messages[$key]['user']['avatar'] = $message->getUser()->getAvatar();
                }
            } else {
                $messages = '';
            }

            return new JsonResponse([
                'messages' => $messages,
                'limit' => $limit,
                'hideBtn' => empty($anotherOne)
            ]);
        }
        return false;
    }

    /**
     * @Route("/ajax/message/delete/{id}", name="message.ajax.delete", methods={"DELETE"})
     *
     * @param Request $request
     * @param Message $message
     *
     * @return bool|JsonResponse
     */
    public function ajaxDelete(Request $request, Message $message): JsonResponse
    {
        $isAjax = $request->isXmlHttpRequest();
        if ($isAjax) {
            $this->entityManager->remove($message);
            $this->entityManager->flush();

            return new JsonResponse(['success' => true]);
        }
        return false;
    }
}
