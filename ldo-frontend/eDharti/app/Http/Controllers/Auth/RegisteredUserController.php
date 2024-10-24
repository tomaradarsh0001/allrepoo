<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Otp;
use App\Models\OldColony;
use App\Models\UserRegistration;
use App\Models\PropertySectionMapping;
use App\Models\Template;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Services\ColonyService;
use App\Services\MisService;
use App\Services\CommunicationService;
use App\Helpers\GeneralFunctions;
use App\Services\SettingsService;


use App\Mail\OtpVerification;
use App\Mail\SuccessfullRegistration;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

use App\Mail\CommonMail;
use App\Models\Country;

class RegisteredUserController extends Controller
{
    protected $communicationService;
    protected $settingsService;

    public function __construct(CommunicationService $communicationService, SettingsService $settingsService)
    {
        $this->communicationService = $communicationService;
        $this->settingsService = $settingsService;
    }

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->syncRoles('user');

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }


    public function publicRegister(ColonyService $colonyService, MisService $misService)
    {
        $colonyList = $colonyService->getColonyList();
        $propertyTypes = $misService->getItemsByGroupId(1052);
        $countries = Country::where('phonecode','!=',0)->orderBy('name','asc')->get();
        return view('auth.public-register', compact(['colonyList', 'propertyTypes', 'countries']));
    }

    public function publicRegisterCreate(Request $request, CommunicationService $communicationService)
    {
        try {
            if (isset($request->purposeReg)) {
                $purposeOfRegistration = $request->purposeReg;
                if (isset($request->newUser)) {
                    $saleDeedDoc = $builAgreeDoc = $leaseDeedDoc = $subMutLtrDoc = $otherDoc = $ownLeaseDoc = $authSignatory = '';
                    $prefix = '';
                    //Introduce Flat Details By Lalit (14 October)
                    if ($request->newUser == 'propertyowner') {
                        /**code is added to get age of applicant. Do not allow the registration if user is less than 18 */
                        $dob = $age = null;
                        if ($request->dateOfBirth) {
                            $dob = $request->dateOfBirth;
                            $age = getAge($dob);
                            if ($age < 18) {
                                return redirect()->back()->with('failure', config('messages.userRegistration.error.age'));
                            }
                        }

                        $userType     = 'individual';
                        $name         = $request->nameInv;
                        $email        = $request->emailInv;
                        $countryCode  = $request->countryCode;
                        $mobileNo     = $request->mobileInv;
                        $consent      = $request->consentInv;
                        $aadhar       = $request->adharnumberInv;
                        $remark       = $request->remarkInv;
                        $saleDeedDoc  = $request->saleDeedDocInv;
                        $BuilAgreeDoc = $request->BuilAgreeDocInv;
                        $leaseDeedDoc = $request->leaseDeedDocInv;
                        $subMutLtrDoc = $request->subMutLtrDocInv;
                        $otherDoc     = $request->otherDocInv;
                        $ownLeaseDoc  = $request->ownLeaseDocInv;
                        $prefix       = $request->prefixInv;
                        $propertyDetailsFilled = $request->propertyId;
                        if (isset($request->propertyId)) {
                            $locality   = $request->localityInvFill;
                            $block      = $request->blocknoInvFill;
                            $plot       = $request->plotnoInvFill;
                            $knownAs    = $request->knownasInvFill;
                            $landUseType    = $request->landUseInvFill;
                            $landUseSubType    = $request->landUseSubtypeInvFill;
                            if (isset($request->isPropertyFlat)) {
                                //Introduce Flat Details By Lalit (14 October)
                                $flat_id = !empty($request->flat) ? $request->flat : null;
                                $flat_no = !empty($request->propertyId_flat_no) ? $request->propertyId_flat_no : null;
                                $is_property_flat = $request->has('isPropertyFlat') ? 1 : 0;
                            }
                        } else {
                            $locality   = $request->localityInv;
                            $block      = $request->blockInv;
                            $plot       = $request->plotInv;
                            $knownAs    = $request->knownasInv;
                            $landUseType    = $request->landUseInv;
                            $landUseSubType    = $request->landUseSubtypeInv;
                            if (isset($request->isPropertyFlat)) {
                                //Introduce Flat Details By Lalit (14 October)
                                $flat_id = !empty($request->flat) ? $request->flat : null;
                                if (!empty($flat_id)) {
                                    $flat_no = !empty($request->flat_no) ? $request->flat_no : null;
                                } else {
                                    if($request->isFlatNotInList){
                                        $flat_no = !empty($request->flat_no) ? $request->flat_no : null;
                                    } else {
                                        $flat_no = !empty($request->flat_no_rec_not_found) ? $request->flat_no_rec_not_found : null;
                                    }
                                }
                                $is_property_flat = $request->has('isPropertyFlat') ? 1 : 0;
                            }
                        }
                    } else {
                        $userType     = 'organization';
                        $name         = $request->nameauthsignatory;
                        $email        = $request->emailauthsignatory;
                        $countryCode  = $request->countryCodeAuthSignatory;
                        $mobileNo     = $request->mobileauthsignatory;
                        $consent      = $request->consentOrg;
                        $aadhar       = $request->orgAddharNo;
                        $remark       = $request->remarkOrg;
                        $saleDeedDoc  = $request->saleDeedOrg;
                        $BuilAgreeDoc = $request->builBuyerAggrmentDoc;
                        $leaseDeedDoc = $request->leaseDeedDoc;
                        $subMutLtrDoc = $request->subMutLetterDoc;
                        $otherDoc     = $request->otherDoc;
                        $propertyDetailsFilled = $request->propertyIdOrg;
                        if (isset($request->propertyIdOrg)) {
                            $locality   = $request->localityOrgFill;
                            $block      = $request->blocknoOrgFill;
                            $plot       = $request->plotnoOrgFill;
                            $knownAs    = $request->knownasOrgFill;
                            $landUseType    = $request->landUseOrgFill;
                            $landUseSubType    = $request->landUseSubtypeOrgFill;
                            if (isset($request->isPropertyFlatOrg)) {
                                //Introduce Flat Details By Lalit (14 October)
                                $flat_id = !empty($request->flatOrg) ? $request->flatOrg : null;
                                $flat_no = !empty($request->propertyIdOrg_flat_no_org) ? $request->propertyIdOrg_flat_no_org : null;
                                $is_property_flat = $request->has('isPropertyFlatOrg') ? 1 : 0;
                            }
                        } else {
                            $locality   = $request->localityOrg;
                            $block      = $request->blockOrg;
                            $plot       = $request->plotOrg;
                            $knownAs    = $request->knownasOrg;
                            $landUseType    = $request->landUseOrg;
                            $landUseSubType    = $request->landUseSubtypeOrg;
                            if (isset($request->isPropertyFlatOrg)) {
                                //Introduce Flat Details By Lalit (14 October)
                                $flat_id = $request->flatOrg;
                                if (!empty($flat_id)) {
                                    $flat_no = $request->flat_no_org;
                                } else {
                                    $flat_no = !empty($request->flat_no_org_rec_not_found) ? $request->flat_no_org_rec_not_found : null;
                                }
                                $is_property_flat = $request->has('isPropertyFlatOrg') ? 1 : 0;
                            }
                        }
                    }

                    //Check is Property related to flat - Lalit Tiwari (15/Oct/2024)
                    if($request->has('isPropertyFlat') || $request->has('isPropertyFlatOrg')){
                        //to check is any registration done for the same  flat property - Lalit Tiwari (15/Oct/2024)
                        $isRegistrationAvailable = UserRegistration::where('locality', $locality)
                            ->where('block', $block)
                            ->where('plot', $plot)
                            ->where('flat_no', $flat_no)
                            ->first();
                    } else {
                        //to check is any registration done for the same property - Sourav Chauhan (10/sep/2024)
                        $isRegistrationAvailable = UserRegistration::where('locality', $locality)
                            ->where('block', $block)
                            ->where('plot', $plot)
                            ->first();
                    }

                    if (empty($isRegistrationAvailable)) {
                        //to check is any registration done with same email or mobile - Sourav Chauhan (10/sep/2024)
                        $ismobileEmailAvailable = UserRegistration::where('mobile', $mobileNo)
                            ->orWhere('email', $email)
                            ->first();
                        if (empty($ismobileEmailAvailable)) {

                            $section = PropertySectionMapping::where('colony_id', $locality)
                                ->where('property_type', $landUseType)
                                ->where('property_subtype', $landUseSubType)
                                ->pluck('section_id')->first();

                            if (!isset($section)) {
                                $section  = 0;
                            }
                            $isEmailMobileVeified = Otp::where('email', $email)
                                ->where('is_email_verified', '1')
                                ->where('mobile', $mobileNo)
                                ->where('is_mobile_verified', '1')
                                ->first();
                            if ($isEmailMobileVeified) {
                                $ownLeaseDoc = '';
                                $authSignatory = '';
                                //get unique registration number
                                $registrationNumber = GeneralFunctions::generateRegistrationNumber();
                                $date = now()->format('Y-m-d');
                                $colony = OldColony::find($locality);
                                $colonyCode = $colony->code;

                                if (isset($saleDeedDoc) && $saleDeedDoc != '') {
                                    $saleDeedDoc = GeneralFunctions::uploadFile($saleDeedDoc, $registrationNumber . '/' . $colonyCode . '/registration', 'saledeed');
                                }
                                if (isset($BuilAgreeDoc) && $BuilAgreeDoc != '') {
                                    $builAgreeDoc = GeneralFunctions::uploadFile($BuilAgreeDoc, $registrationNumber . '/' . $colonyCode . '/registration', 'BuilderAgreement');
                                }
                                if (isset($leaseDeedDoc) && $leaseDeedDoc != '') {
                                    $leaseDeedDoc = GeneralFunctions::uploadFile($leaseDeedDoc, $registrationNumber . '/' . $colonyCode . '/registration', 'leaseDeed');
                                }
                                if (isset($subMutLtrDoc) && $subMutLtrDoc != '') {
                                    $subMutLtrDoc = GeneralFunctions::uploadFile($subMutLtrDoc, $registrationNumber . '/' . $colonyCode . '/registration', 'subsMutLetter');
                                }
                                if (isset($otherDoc) && $otherDoc != '') {
                                    $otherDoc = GeneralFunctions::uploadFile($otherDoc, $registrationNumber . '/' . $colonyCode . '/registration', 'otherDocuments');
                                }
                                if (isset($request->ownLeaseDocInv) && $request->ownLeaseDocInv != '') {
                                    $ownLeaseDoc = GeneralFunctions::uploadFile($request->ownLeaseDocInv, $registrationNumber . '/' . $colonyCode . '/registration', 'ownerLessee');
                                }
                                if (isset($request->propDoc) && $request->propDoc != '') {
                                    $authSignatory = GeneralFunctions::uploadFile($request->propDoc, $registrationNumber . '/' . $colonyCode . '/registration', 'ownerLessee');
                                }
                                $userRegistration = UserRegistration::create([
                                    'applicant_number' => $registrationNumber,
                                    'status' => getStatusName('RS_NEW'),
                                    'purpose_of_registation' => $purposeOfRegistration,
                                    'user_type' => $userType,
                                    'name' => $name,
                                    'gender' => $request->genderInv ? $request->genderInv : '',
                                    'dob' => $dob ?? null,
                                    'age' => $age ?? 0,
                                    'prefix' => $prefix,
                                    'second_name' => $request->secondnameInv ? $request->secondnameInv : '',
                                    'country_code' => $countryCode ? $countryCode : null,
                                    'mobile' => $mobileNo,
                                    'email' => $email,
                                    'pan_number' => $request->pannumberInv ? $request->pannumberInv : '',
                                    'aadhar_number' => $aadhar,
                                    'user_remark' => $remark,
                                    'comm_address' => $request->commAddressInv ? $request->commAddressInv : '',
                                    'is_property_id_known' => $propertyDetailsFilled,
                                    'locality' => $locality,
                                    'block' => $block,
                                    'plot' => $plot,
                                    'flat_id' => $flat_id ?? null,
                                    'is_property_flat' => $is_property_flat ?? 0,
                                    'flat_no' => $flat_no ?? null,
                                    'known_as' => $knownAs,
                                    'land_use_type' => $landUseType,
                                    'land_use_sub_type' => $landUseSubType,
                                    'section_id' => $section,
                                    'organization_name' => $request->nameOrg ? $request->nameOrg : '',
                                    'organization_pan_card' => $request->pannumberOrg ? $request->pannumberOrg : '',
                                    'organization_address' => $request->orgAddressOrg ? $request->orgAddressOrg : '',
                                    'sale_deed_doc' => $saleDeedDoc,
                                    'builder_buyer_agreement_doc' => $builAgreeDoc,
                                    'lease_deed_doc' => $leaseDeedDoc,
                                    'substitution_mutation_letter_doc' => $subMutLtrDoc,
                                    'other_doc' => $otherDoc,
                                    'owner_lessee_doc' => $ownLeaseDoc,
                                    'authorised_signatory_doc' => $authSignatory,
                                    'chain_of_ownership_doc' => '',
                                    'consent' => $consent
                                ]);

                                if ($userRegistration) {
                                    $data = [
                                        'name' => $name,
                                        'email' => $email,
                                        'regNo' => $registrationNumber
                                    ];
                                    $action = 'REG_SUC';
                                    // Apply the mail settings before sending the email
                                    $this->settingsService->applyMailSettings($action);
                                    $mailSent = Mail::to($email)->send(new CommonMail($data, $action));
                                    $communicationService->sendSmsMessage($data, $mobileNo, $action);
                                    $communicationService->sendWhatsAppMessage($data, $mobileNo, $action);

                                    // return redirect()->back()->with('success', 'You are Registared successfully, and your registration no. is:- ' . $registrationNumber);
                                    return view('auth.registration-success', compact(['registrationNumber']));
                                } else {
                                    // return redirect()->back()->with('failure', 'Registration not successfull');
                                    return view('auth.registration-failed');
                                }
                            } else {
                                return redirect()->back()->with('failure', 'You email or mobile not verified. Please verify.');
                            }
                        } else {
                            return redirect()->back()->with('failure', 'Mobile number or email is already in use');
                        }
                    } else {
                        return redirect()->back()->with('failure', 'Registration for your property already available');
                    }
                } else {
                    return redirect()->back()->with('failure', 'Property Type not available');
                }
            } else {
                return redirect()->back()->with('failure', 'Purpose of Registration is required');
            }
        } catch (\Exception $e) {
            Log::info($e);
            return redirect()->back()->with('failure', $e->getMessage());
        }
    }


    //To send and store the otp - Sourav Chauhan (25/july/2024)
    public function saveOtp(Request $request, CommunicationService $communicationService)
    {
        try {
            if (isset($request->mobile) && isset($request->countryCode)) {
                // $isMobileAvailable = Otp::where('mobile', $request->mobile)->where('is_mobile_verified', '1')->first();
                //Added multiple where condition in single where - Lalit tiwari (18/Oct/2024)
                $isMobileAvailable = Otp::where([['country_code', $request->countryCode],['mobile', $request->mobile],['is_mobile_verified', '1']])->first();

                if (!$isMobileAvailable) {
                    $generateOtp = GeneralFunctions::generateUniqueRandomNumber(4);

                    if (isset($request->emailToVerify)) {
                        $isEmailVerified = Otp::where('email', $request->emailToVerify)->first();
                        if ($isEmailVerified) {
                            $isEmailVerified->country_code = $request->countryCode;
                            $isEmailVerified->mobile = $request->mobile;
                            $isEmailVerified->mobile_otp = $generateOtp;
                            $isEmailVerified->mobile_otp_sent_at = now();
                            if ($isEmailVerified->save()) {

                                $action = 'OTP_VALID';
                                $data = [
                                    'otp' => $generateOtp
                                ];
                                Log::info("your otp is " . $generateOtp);
                                $communicationService->sendSmsMessage($data, $request->mobile, $action, $request->countryCode);
                                $communicationService->sendWhatsAppMessage($data, $request->mobile, $action, $request->countryCode);
                                return response()->json(['success' => true, 'message' => 'OTP sent to mobile number ' . $request->mobile . ' successfully']);
                            } else {
                                return response()->json(['success' => false, 'message' => 'Failed to send OTP']);
                            }
                        }
                    }

                    // Create or update OTP record for the mobile
                    $otp = Otp::updateOrCreate(
                        [
                            'country_code' => $request->countryCode,
                            'mobile' => $request->mobile,
                        ],
                        [
                            'mobile_otp' => $generateOtp,
                            'mobile_otp_sent_at' => now(),
                        ]
                    );
                    
                    if ($otp) {
                        $action = 'OTP_VALID';
                        $data = [
                            'otp' => $generateOtp
                        ];
                        Log::info("your otp is " . $generateOtp);
                        $communicationService->sendSmsMessage($data, $request->mobile, $action, $request->countryCode);
                        $communicationService->sendWhatsAppMessage($data, $request->mobile, $action, $request->countryCode);
                        return response()->json(['success' => true, 'message' => 'OTP sent to mobile number ' . $request->mobile . ' successfully']);
                    } else {
                        return response()->json(['success' => false, 'message' => 'Failed to send OTP']);
                    }
                } else {
                    return response()->json(['success' => false, 'message' => 'Mobile already in use']);
                }
            } else {
                //for email otp
                $isEmailAvailable = Otp::where('email', $request->email)->where('is_email_verified', '1')->first();

                if (!$isEmailAvailable) {
                    $generateOtp = GeneralFunctions::generateUniqueRandomNumber(4);

                    // Apply the mail settings before sending the email
                    $action = 'OTP_VALID';
                    $this->settingsService->applyMailSettings($action);
                    $data = [
                        'otp' => $generateOtp
                    ];
                    Log::info("your email otp is " . $generateOtp);

                    $mailSent = Mail::to($request->email)->send(new CommonMail($data, $action));

                    if ($mailSent) {
                        if (isset($request->mobileToVerify)) {
                            $isMobileVerified = Otp::where('mobile', $request->mobileToVerify)->first();
                            if ($isMobileVerified) {
                                $isMobileVerified->email = $request->email;
                                $isMobileVerified->email_otp = $generateOtp;
                                $isMobileVerified->email_otp_sent_at = now();
                                $isMobileVerified->save();
                                return response()->json(['success' => true, 'message' => 'OTP sent to email ' . $request->email . ' successfully']);
                            }
                        }
                        // Create or update OTP record for the email
                        $otp = Otp::updateOrCreate(
                            ['email' => $request->email],
                            ['email_otp' => $generateOtp, 'email_otp_sent_at' => now()]
                        );
                        Log::info("your email otp is " . $generateOtp);

                        if ($otp) {
                            return response()->json(['success' => true, 'message' => 'OTP sent to email ' . $request->email . ' successfully']);
                        } else {
                            return response()->json(['success' => false, 'message' => 'Failed to send OTP']);
                        }
                    } else {
                        return response()->json(['success' => false, 'message' => 'Failed to send OTP']);
                    }
                } else {
                    return response()->json(['success' => false, 'message' => 'Email already in use']);
                }
            }
        } catch (\Exception $e) {
            Log::info($e);
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    //To verify the otp - Sourav Chauhan (25/july/2024)
    public function verifyOtp(Request $request)
    {
        try {
            if (isset($request->mobileOtp)) {
                $databaseOtp = Otp::where('mobile', $request->mobile)->where('mobile_otp', $request->mobileOtp)->first();
                if ($databaseOtp) {
                    $databaseOtp->is_mobile_verified = '1';
                    $databaseOtp->mobile_otp = null;
                    if ($databaseOtp->save()) {
                        return response()->json(['success' => true, 'message' => 'Mobile number ' . $request->mobile . ' verified successfully']);
                    } else {
                        return response()->json(['success' => false, 'message' => 'Mobile number not verified']);
                    }
                } else {
                    return response()->json(['success' => false, 'message' => 'Otp not matched. Please enter correct otp']);
                }
            } else if ($request->emailOtp) {
                //email otp
                $databaseOtp = Otp::where('email', $request->email)->where('email_otp', $request->emailOtp)->first();
                if ($databaseOtp) {
                    $databaseOtp->is_email_verified = '1';
                    $databaseOtp->email_otp = null;
                    if ($databaseOtp->save()) {
                        return response()->json(['success' => true, 'message' => 'Email ' . $request->email . ' verified successfully']);
                    } else {
                        return response()->json(['success' => false, 'message' => 'Email not verified']);
                    }
                } else {
                    return response()->json(['success' => false, 'message' => 'Otp not matched. Please enter correct otp']);
                }
            }
        } catch (\Exception $e) {
            Log::info($e);
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function registrationStatus()
    {
        return view('auth.registration-status');
    }
}
