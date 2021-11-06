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
    	$drugs = new Category();
    	$drugs->name = 'Drugs';
    	$drugs->slug = 'drugs';
    	$drugs->save(); 

    	$cannabis = new Category();
    	$cannabis->parent_category = $drugs->id;
    	$cannabis->name = 'Cannabis';
    	$cannabis->slug = 'cannabis';
    	$cannabis->save(); 

    	$dissociatives = new Category();
    	$dissociatives->parent_category = $drugs->id;
    	$dissociatives->name = 'Dissociatives';
    	$dissociatives->slug = 'dissociatives';
    	$dissociatives->save(); 

    	$ectasy = new Category();
    	$ectasy->parent_category = $drugs->id;
    	$ectasy->name = 'Ectasy';
    	$ectasy->slug = 'ectasy';
    	$ectasy->save(); 

    	$opioids = new Category();
    	$opioids->parent_category = $drugs->id;
    	$opioids->name = 'Opioids';
    	$opioids->slug = 'opioids';
    	$opioids->save(); 

    	$other = new Category();
    	$other->parent_category = $drugs->id;
    	$other->name = 'Other';
    	$other->slug = 'other';
    	$other->save(); 

    	$precursors = new Category();
    	$precursors->parent_category = $drugs->id;
    	$precursors->name = 'Precursors';
    	$precursors->slug = 'precursors';
    	$precursors->save(); 

    	$prescription = new Category();
    	$prescription->parent_category = $drugs->id;
    	$prescription->name = 'Prescription';
    	$prescription->slug = 'prescription';
    	$prescription->save(); 

    	$psychedelics = new Category();
    	$psychedelics->parent_category = $drugs->id;
    	$psychedelics->name = 'Psychedelics';
    	$psychedelics->slug = 'psychedelics';
    	$psychedelics->save(); 

    	$stimulants = new Category();
    	$stimulants->parent_category = $drugs->id;
    	$stimulants->name = 'Stimulants';
    	$stimulants->slug = 'stimulants';
    	$stimulants->save(); 

        $art = new Category();
        $art->name = 'Art';
        $art->slug = 'art';
        $art->save(); 

        $books = new Category();
        $books->name = 'Books';
        $books->slug = 'books';
        $books->save(); 

        $computerEquipment = new Category();
        $computerEquipment->name = 'Computer equipment';
        $computerEquipment->slug = 'computer-equipment';
        $computerEquipment->save(); 

        $digitalGoods = new Category();
        $digitalGoods->name = 'Digital goods';
        $digitalGoods->slug = 'digital-goods';
        $digitalGoods->save(); 

        $drugParaphernalia = new Category();
        $drugParaphernalia->name = 'Drug paraphernalia';
        $drugParaphernalia->slug = 'drug-paraphernalia';
        $drugParaphernalia->save(); 

        $electronics = new Category();
        $electronics->name = 'Electronics';
        $electronics->slug = 'electronics';
        $electronics->save(); 

        $erotica = new Category();
        $erotica->name = 'Erotica';
        $erotica->slug = 'erotica';
        $erotica->save(); 

        $fireworks = new Category();
        $fireworks->name = 'Fireworks';
        $fireworks->slug = 'fireworks';
        $fireworks->save(); 

        $hardware = new Category();
        $hardware->name = 'Hardware';
        $hardware->slug = 'hardware';
        $hardware->save(); 

        $herbsAndSupplements = new Category();
        $herbsAndSupplements->name = 'Herbs & Supplements';
        $herbsAndSupplements->slug = 'herbs-and-supplements';
        $herbsAndSupplements->save(); 

        $homeAndGarden = new Category();
        $homeAndGarden->name = 'Home & Garden';
        $homeAndGarden->slug = 'home-and-garden';
        $homeAndGarden->save();

        $jewelry = new Category();
        $jewelry->name = 'Jewelry';
        $jewelry->slug = 'jewelry';
        $jewelry->save();

        $lotteriesAndGames = new Category();
        $lotteriesAndGames->name = 'Lotteries & games';
        $lotteriesAndGames->slug = 'Lotteries & games';
        $lotteriesAndGames->save(); 

        $medical = new Category();
        $medical->name = 'Medical';
        $medical->slug = 'medical';
        $medical->save(); 

        $money = new Category();
        $money->name = 'Money';
        $money->slug = 'money';
        $money->save(); 

        $services = new Category();
        $services->name = 'Services';
        $services->slug = 'services';
        $services->save(); 

        $weightLoss = new Category();
        $weightLoss->name = 'Weight loss';
        $weightLoss->slug = 'weight-loss';
        $weightLoss->save(); 
    }
}
