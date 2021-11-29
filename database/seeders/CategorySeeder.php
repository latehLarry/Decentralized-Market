<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ########### BENZOS
        $benzos = new Category();
        $benzos->name = 'Benzos';
        $benzos->slug = 'benzos';
        $benzos->save();

            $benzos = new Category();
            $pills->parent_category = $benzos->id;
            $benzos->name = 'Pills';
            $benzos->slug = 'pills';
            $benzos->save();

            $powder = new Category();
            $powder->parent_category = $benzos->id;
            $powder->name = 'Powder';
            $powder->slug = 'powder';
            $powder->save();


        ########### CANNABIS
        $cannabis = new Category();
        $cannabis->name = 'Cannabis';
        $cannabis->slug = 'cannabis';
        $cannabis->save();

            $budsAndFlower = new Category();
            $budsAndFlower->parent_category = $cannabis->id;
            $budsAndFlower->name = 'Buds and Flower';
            $budsAndFlower->slug = 'buds-and-flower';
            $budsAndFlower->save();

            $edibles = new Category();
            $edibles->parent_category = $cannabis->id;
            $edibles->name = 'Edibles';
            $edibles->slug = 'edibles';
            $edibles->save();

            $concentrates = new Category();
            $concentrates->parent_category = $cannabis->id;
            $concentrates->name = 'Concentrates';
            $concentrates->slug = 'concentrates';
            $concentrates->save();

            $hash = new Category();
            $hash->parent_category = $cannabis->id;
            $hash->name = 'Hash';
            $hash->slug = 'hash';
            $hash->save();


        ########### DISSOCIATIVES
        $dissociatives = new Category();
        $dissociatives->name = 'Dissociatives';
        $dissociatives->slug = 'dissociatives ';
        $dissociatives->save();

            $ghb = new Category();
            $ghb->parent_category = $dissociatives->id;
            $ghb->name = 'GHB';
            $ghb->slug = 'ghb';
            $ghb->save();

            $mxe = new Category();
            $mxe->parent_category = $dissociatives->id;
            $mxe->name = 'MXE';
            $mxe->slug = 'mxe';
            $mxe->save();

            $ketamine = new Category();
            $ketamine->parent_category = $dissociatives->id;
            $ketamine->name = 'Ketamine';
            $ketamine->slug = 'ketamine';
            $ketamine->save();


        ########### ECSTASY
        $ecstasy = new Category();
        $ecstasy->name = 'Ecstasy';
        $ecstasy->slug = 'ecstasy';
        $ecstasy->save();

            $mdma = new Category();
            $mdma->parent_category = $ecstasy->id;
            $mdma->name = 'MDMA';
            $mdma->slug = 'mdma';
            $mdma->save();

            $mda = new Category();
            $mda->parent_category = $ecstasy->id;
            $mda->name = 'MDA';
            $mda->slug = 'mda';
            $mda->save();

            $methyloneAndBK = new Category();
            $methyloneAndBK->parent_category = $ecstasy->id;
            $methyloneAndBK->name = 'Methylone and BK';
            $methyloneAndBK->slug = 'methylone-and-bk';
            $methyloneAndBK->save();

            
        ########### OPIOIDS
        $opioids = new Category();
        $opioids->name = 'Opioids';
        $opioids->slug = 'opioids';
        $opioids->save();

            $codeine = new Category();
            $codeine->parent_category = $codeine->id;
            $codeine->name = 'Codeine';
            $codeine->slug = 'codeine';
            $codeine->save();

            $heroin = new Category();
            $heroin->parent_category = $opioids->id;
            $heroin->name = 'Heroin';
            $heroin->slug = 'heroin';
            $heroin->save();

            $morphine = new Category();
            $morphine->parent_category = $opioids->id;
            $morphine->name = 'Morphine';
            $morphine->slug = 'morphine ';
            $morphine->save();

            $oxy = new Category();
            $oxy->parent_category = $opioids->id;
            $oxy->name = 'Oxy';
            $oxy->slug = 'oxy';
            $oxy->save();

            $crack = new Category();
            $crack->parent_category = $opioids->id;
            $crack->name = 'Crack';
            $crack->slug = 'crack';
            $crack->save();


        ########### PRESCRIPTION
        $prescription = new Category();
        $prescription->name = 'Prescription';
        $prescription->slug = 'prescription';
        $prescription->save();


        ########### PSYCHEDELICS
        $psychedelics = new Category();
        $psychedelics->name = 'Psychedelics';
        $psychedelics->slug = 'psychedelics';
        $psychedelics->save();

            $lsd = new Category();
            $lsd->parent_category = $psychedelics->id;
            $lsd->name = 'LSD';
            $lsd->slug = 'lsd';
            $lsd->save();

            $dmt = new Category();
            $dmt->parent_category = $psychedelics->id;
            $dmt->name = 'DMT';
            $dmt->slug = 'dmt';
            $dmt->save();

            $shrooms = new Category();
            $shrooms->parent_category = $psychedelics->id;
            $shrooms->name = 'Shrooms';
            $shrooms->slug = 'shrooms';
            $shrooms->save();

            $2cb = new Category();
            $2cb->parent_category = $psychedelics->id;
            $2cb->name = '2C-B';
            $2cb->slug = '2c-b';
            $2cb->save();


        ########### STEROIDS
        $steroids = new Category();
        $steroids->name = 'Steroids';
        $steroids->slug = 'steroids';
        $steroids->save();


        ########### STIMULANTS
        $stimulants = new Category();
        $stimulants->name = 'Stimulants';
        $stimulants->slug = 'stimulants';
        $stimulants->save();

            $speed = new Category();
            $speed->parent_category = $stimulants->id;
            $speed->name = 'Speed';
            $speed->slug = 'speed';
            $speed->save();

            $meth = new Category();
            $meth->parent_category = $stimulants->id;
            $meth->name = 'Meth';
            $meth->slug = 'meth';
            $meth->save();

            $cocaine = new Category();
            $cocaine->parent_category = $stimulants->id;
            $cocaine->name = 'Cocaine';
            $cocaine->slug = 'cocaine';
            $cocaine->save();

            $mephedrone = new Category();
            $mephedrone->parent_category = $stimulants->id;
            $mephedrone->name = 'Mephedrone';
            $mephedrone->slug = 'mephedrone';
            $mephedrone->save();

            $crack = new Category();
            $crack->parent_category = $stimulants->id;
            $crack->name = 'Crack';
            $crack->slug = 'crack';
            $crack->save();


        ########### TOBACCO
        $tobacco = new Category();
        $tobacco->name = 'Tobacco';
        $tobacco->slug = 'tobacco';
        $tobacco->save();


        ########### WEIGHT LOSS
        $weightLoss = new Category();
        $weightLoss->name = 'Weight Loss';
        $weightLoss->slug = 'weight-loss';
        $weightLoss->save();


        ########### PARAPHERNALIA
        $paraphernalia = new Category();
        $paraphernalia->name = 'Paraphernalia';
        $paraphernalia->slug = 'paraphernalia';
        $paraphernalia->save();


        ########### OTHERS
        $others = new Category();
        $others->name = 'Others';
        $others->slug = 'others';
        $others->save();
    }
}
