<?php

namespace JGI\AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Nelmio\ApiDocBundle\Annotation as ApiDoc;

class ApiController extends Controller
{
    /**
     * Get date
     *
     * <h4>Response</h4>
     * <pre>
     * {
     *   "date":"2014-12-25",
     *   "red_day":true,
     *   "name":"Juldagen"
     * }
     * </pre>
     *
     * @ApiDoc\ApiDoc(
     *  section="Date",
     *  statusCodes={
     *    200="Returned when successful",
     *    400="Returned when date is invalid"
     *  }
     * )
     * @Rest\Get("/dates/{date}")
     * @Rest\View()
     */
    public function dateAction($date)
    {
        $this->forward400IfDateIsNotValid($date);
        $date = $this->get('jgi.swedish_dates.datemanager')->getDate(new \Datetime($date));

        return [
            'date' => $date->getDateTime()->format('Y-m-d'),
            'red_day' => $date->isRedDay(),
            'name' => $date->getName(),
        ];
    }

    /**
     * @param string $date
     *
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    protected function forward400IfDateIsNotValid($date)
    {
        try {
            new \DateTime($date);
        } catch (\Exception $e) {
            throw new BadRequestHttpException(sprintf('Date "%s" is not valid', $date));
        }
    }
}
