<?php

namespace Sylius\Bundle\ReportBundle\DataFetcher;

use Sylius\Bundle\CoreBundle\Doctrine\ORM\UserRepository;
use Sylius\Component\Report\DataFetcher\DataFetcherInterface;
use Sylius\Component\Report\DataFetcher\Data;

/**
* User registration data fetcher
* 
* @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
*/
class UserRegistrationDataFetcher implements DataFetcherInterface
{
    const PERIOD_DAY    = 'day';
    const PERIOD_MONTH  = 'month';
    const PERIOD_YEAR   = 'year';

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function fetch(array $configuration){
        $data = new Data();

        switch ($configuration['period']) {
            case self::PERIOD_DAY:
                $rawData = $this->userRepository->getDailyStatistic($configuration);
                $configuration['interval'] = 'P1D';
                $configuration['periodFormat'] = '%a';
                $configuration['presentationFormat'] = 'Y-m-d';
                break;
            case self::PERIOD_MONTH:
                $rawData = $this->userRepository->getMonthlyStatistic($configuration);
                $configuration['interval'] = 'P1M';
                $configuration['periodFormat'] = '%m';
                $configuration['presentationFormat'] = 'F Y';
                break;
            case self::PERIOD_YEAR:
                $rawData = $this->userRepository->getYearlyStatistic($configuration);
                $configuration['interval'] = 'P1Y';
                $configuration['periodFormat'] = '%y';
                $configuration['presentationFormat'] = 'Y';
                break;
            default:
                throw new \InvalidArgumentException('Wrong data fetcher period');
                break;
        }

        if (empty($rawData)) {
            return $data;
        }

        $labels = array_keys($rawData[0]);
        $data->setLabels($labels);

        $fetched = array();

        if ($configuration['empty_records']) {
            $fetched = $this->fillEmptyRecodrs($fetched,$configuration);
        }

        foreach ($rawData as $row) {
            $fetched[$row[$labels[0]]] = $row[$labels[1]];
        }

        $data->setData($fetched);

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function getType(){
        return 'user_registration';
    }

    public static function getPeriodChoices()
    {
        return array(
            self::PERIOD_DAY    => 'Daily',
            self::PERIOD_MONTH  => 'Monthly',
            self::PERIOD_YEAR   => 'Yearly');
    }

    private function fillEmptyRecodrs(array $fetched, array $configuration)
    {
        $date = $configuration['start'];
        $diff1Day = new \DateInterval($configuration['interval']);
        $numberOfPeriods = $configuration['start']->diff($configuration['end']);
        for ($i=0; $i < $numberOfPeriods->format($configuration['periodFormat']); $i++) {
            $fetched[$date->format($configuration['presentationFormat'])] = 0;
            $date = $date->add($diff1Day);
        }
        return $fetched;
    }
}