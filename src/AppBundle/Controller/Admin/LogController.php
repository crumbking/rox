<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Member;
use AppBundle\Form\LogFormType;
use AppBundle\Model\LogModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LogController extends Controller
{
    /**
     * @Route("/admin/logs", name="admin_logs_overview")
     *
     * @param Request $request
     *
     * @throws \Doctrine\ORM\ORMException
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showOverview(Request $request)
    {
        $member = null;
        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 20);
        $types = $request->query->get('types', []);
        $username = $request->query->get('username', null);

        $logModel = new LogModel($this->getDoctrine());
        $logTypes = $logModel->getLogTypes();

        $logForm = $this->createForm(LogFormType::class, [
            'logTypes' => $logTypes,
        ]);
        $logForm->handleRequest($request);

        if ($logForm->isSubmitted() && $logForm->isValid()) {
            $data = $logForm->getData();
            $types = $data['types'];
            $username = $data['username'];
        }
        if (null !== $username) {
            $memberRepository = $this->getDoctrine()->getRepository(Member::class);
            $member = $memberRepository->loadUserByUsername($username);
        }

        $logs = $logModel->getFilteredLogs($types, $member, $page, $limit);

        return  $this->render(':admin:logs/index.html.twig', [
            'form' => $logForm->createView(),
            'logs' => $logs,
        ]);
    }
}