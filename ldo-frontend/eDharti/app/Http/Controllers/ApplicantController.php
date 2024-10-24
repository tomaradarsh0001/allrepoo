<?php

namespace App\Http\Controllers;

use App\Helpers\GeneralFunctions;
use App\Http\Controllers\Controller;
use App\Models\ApplicantUserDetail;
use App\Models\NewlyAddedProperty;
use App\Models\OldColony;
use App\Models\PropertyMaster;
use App\Models\PropertySectionMapping;
use App\Models\User;
use App\Models\UserProperty;
use App\Services\ColonyService;
use App\Services\MisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicantController extends Controller
{
    public function index(Request $request)
    {
        $user = User::find(Auth::id());
        // $details = $user->applicationUserDetail;
        // dd($details);

        return view('applicant.index', ['user' => $user]);
    }

    public function propertiesDetails(ColonyService $colonyService, MisService $misService)
    {
        $user = User::with('applicantUserDetails', 'userProperties.documents')->findOrFail(Auth::id());
        $colonyList = $colonyService->getColonyList();
        $propertyTypes = $misService->getItemsByGroupId(1052);
        //Get Newly Added Property Details
        $newProperties = NewlyAddedProperty::where('user_id',Auth::id())->where('status',getStatusName('RS_PEN'))->get();
        // dd($user->userProperties);
        return view('applicant.property', compact('user', 'colonyList', 'propertyTypes','newProperties'));
    }


    public function applicationHistory()
    {
        return view('applicant.application_history');
    } 

    public function storeNewProperty(Request $request)
    {
        $userId = Auth::id();
        if (isset($request->propertyId)) {
            $locality   = $request->localityInvFill;
            $block      = $request->blocknoInvFill;
            $plot       = $request->plotnoInvFill;
            $knownAs    = $request->knownasInvFill;
            $landUseType    = $request->landUseInvFill;
            $landUseSubType    = $request->landUseSubtypeInvFill;
        } else {
            $locality   = $request->localityInv;
            $block      = $request->blockInv;
            $plot       = $request->plotInv;
            $knownAs    = $request->knownasInv;
            $landUseType    = $request->landUseInv;
            $landUseSubType    = $request->landUseSubtypeInv;
        }
        $saleDeedDoc  = $request->saleDeedDocInv;
        $builAgreeDoc = $request->BuilAgreeDocInv;
        $leaseDeedDoc = $request->leaseDeedDocInv;
        $subMutLtrDoc = $request->subMutLtrDocInv;
        $otherDocDoc = $request->otherDocInv;
        $ownerLessDocInv = $request->ownerLessDocInv;

        //Check existing property for applicant
        $propertyExists = UserProperty::where([
            ['user_id', '=', $userId],
            ['locality', '=', $locality],
            ['block', '=', $block],
            ['plot', '=', $plot],
        ])->first();
        if (!empty($propertyExists)) {
            return redirect()->back()->with('failure', 'Property already exists with Property ID ' . $propertyExists->new_property_id);
        }

        //Check property alredy exist for this user or not
        $propertyExists = NewlyAddedProperty::where([
            ['user_id', '=', $userId],
            ['locality', '=', $locality],
            ['block', '=', $block],
            ['plot', '=', $plot],
        ])->exists();
        if ($propertyExists) {
            return redirect()->back()->with('failure', 'Property already added. Please waiting for admin approval.');
        }

        //Fetch Suggested Property Id from Property Master
        $property = PropertyMaster::where('new_colony_name', $locality)->where('block_no', $block)->where('plot_or_property_no', $plot)->first();
        if (!empty($property['id'])) {
            $oldPropertyId = $property['old_propert_id'];
            $suggestedPropertyId = $property['id'];
        } else {
            $oldPropertyId = null;
            $suggestedPropertyId = null;
        }

        $getApplicantNumber = ApplicantUserDetail::where('user_id', $userId)->first();
        if (!empty($getApplicantNumber['id'])) {
            $applicantNumber = $getApplicantNumber['applicant_number'];
        } else {
            $applicantNumber = '';
        }


        $section = PropertySectionMapping::where('colony_id', $locality)
            ->where('property_type', $landUseType)
            ->where('property_subtype', $landUseSubType)
            ->pluck('section_id')->first();

        if (!isset($section)) {
            $section  = 0;
        }

        //get unique registration number
        // $registrationNumber = GeneralFunctions::generateRegistrationNumber();
        $registrationNumber = 'NEW_PROPERTY';
        $date = now()->format('Y-m-d');
        $colony = OldColony::find($locality);
        $colonyCode = $colony->code;
        if (isset($saleDeedDoc)) {
            $saleDeedDoc = GeneralFunctions::uploadFile($saleDeedDoc, $applicantNumber . '/' . $colonyCode . '/other_property/' . $block . '_' . $plot, 'saledeed');
        }
        if (isset($builAgreeDoc)) {
            $builAgreeDoc = GeneralFunctions::uploadFile($builAgreeDoc, $applicantNumber . '/' . $colonyCode . '/other_property/' . $block . '_' . $plot, 'BuilderAgreement');
        }
        if (isset($leaseDeedDoc)) {
            $leaseDeedDoc = GeneralFunctions::uploadFile($leaseDeedDoc, $applicantNumber . '/' . $colonyCode . '/other_property/' . $block . '_' . $plot, 'leaseDeed');
        }
        if (isset($subMutLtrDoc)) {
            $subMutLtrDoc = GeneralFunctions::uploadFile($subMutLtrDoc, $applicantNumber . '/' . $colonyCode . '/other_property/' . $block . '_' . $plot, 'subsMutLetter');
        }
        if (isset($otherDocDoc)) {
            $otherDocDoc = GeneralFunctions::uploadFile($otherDocDoc, $applicantNumber . '/' . $colonyCode . '/other_property/' . $block . '_' . $plot, 'other');
        }
        if (isset($ownerLessDocInv)) {
            $ownerLessDocInv = GeneralFunctions::uploadFile($ownerLessDocInv, $applicantNumber . '/' . $colonyCode . '/other_property/' . $block . '_' . $plot, 'ownerLessee');
        }

        $newProperyAdded = NewlyAddedProperty::create([
            'old_property_id' => $oldPropertyId,
            'suggested_property_id' => $suggestedPropertyId,
            'user_id' => $userId,
            'applicant_number' => $applicantNumber,
            'locality' => $locality,
            'block' => $block,
            'plot' => $plot,
            'known_as' => !empty($knownAs) ? $knownAs : null,
            'land_use_type' => $landUseType,
            'land_use_sub_type' => $landUseSubType,
            'section_id' => $section,
            'sale_deed_doc' => $saleDeedDoc,
            'builder_buyer_agreement_doc' => $builAgreeDoc,
            'lease_deed_doc' => $leaseDeedDoc,
            'substitution_mutation_letter_doc' => $subMutLtrDoc,
            'other_doc' => $otherDocDoc,
            'owner_lessee_doc' => $ownerLessDocInv,
            'status' => getStatusName('RS_PEN')
        ]);

        if ($newProperyAdded) {
            return redirect()->back()->with('success', 'Your property added successfully. Waiting for administrator approval');
        } else {
            return redirect()->back()->with('failure', 'Property not added successfully. Something went wrong');
        }
    }
}
