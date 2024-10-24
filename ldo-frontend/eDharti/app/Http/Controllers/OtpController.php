<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CommunicationService;
use App\Services\SettingsService;
use App\Models\Otp;
use Illuminate\Support\Facades\Mail;
use App\Mail\CommonMail;
use Illuminate\Support\Facades\Log;
use App\Helpers\GeneralFunctions;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class OtpController extends Controller
{
    protected $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    public function saveAptOtp(Request $request, CommunicationService $communicationService)
    {
        try {
            if ($request->has('mobile')) {
                if ($request->has('emailToVerify')) {
                    // Fetch the latest OTP record for the hidden email
                    $latestEmailRecord = Otp::where('email', $request->emailToVerify)
                        ->orderBy('created_at', 'desc')
                        ->first();
    
                    // If the latest record has both email and mobile verified, create a new record for mobile
                    if ($latestEmailRecord && $latestEmailRecord->is_email_verified == '1' && $latestEmailRecord->is_mobile_verified == '1') {
                        $generateOtp = GeneralFunctions::generateUniqueRandomNumber(4);
                        $otp = Otp::create([
                            'mobile' => $request->mobile,
                            'mobile_otp' => $generateOtp,
                            'mobile_otp_sent_at' => now(),
                            'service_type' => getServiceType('APT_NEW')
                        ]);
                        if ($otp) {
                            $action = 'APT_OTP';
                            $data = ['otp' => $generateOtp];
                             
                            //OTP log for testing 
                            Log::info($generateOtp);
                            
                            $communicationService->sendSmsMessage($data, $request->mobile, $action);
                            $communicationService->sendWhatsAppMessage($data, $request->mobile, $action);
                            return response()->json(['success' => true, 'message' => 'OTP sent to mobile number ' . $request->mobile . ' successfully']);
                        }
                    } else if ($latestEmailRecord) {
                        // If only the email verification exists, update the same record with mobile details
                        $generateOtp = GeneralFunctions::generateUniqueRandomNumber(4);
                        $latestEmailRecord->mobile = $request->mobile;
                        $latestEmailRecord->mobile_otp = $generateOtp;
                        $latestEmailRecord->mobile_otp_sent_at = now();
                        if ($latestEmailRecord->save()) {
                            $action = 'APT_OTP';
                            $data = ['otp' => $generateOtp];

                             //OTP log for testing 
                            Log::info($generateOtp);

                            $communicationService->sendSmsMessage($data, $request->mobile, $action);
                            $communicationService->sendWhatsAppMessage($data, $request->mobile, $action);
                            return response()->json(['success' => true, 'message' => 'OTP sent to mobile number ' . $request->mobile . ' successfully']);
                        }
                    }
                }
    
                // Existing logic for the first-time or fallback scenario
                $latestMobileRecord = Otp::where('mobile', $request->mobile)
                ->orderBy('created_at', 'desc')
                ->first();
    
                if (!$latestMobileRecord) {
                    // Existing OTP generation and sending process
                    $generateOtp = GeneralFunctions::generateUniqueRandomNumber(4);
                    $otp = Otp::updateOrCreate(
                        ['mobile' => $request->mobile],
                        ['mobile_otp' => $generateOtp, 'mobile_otp_sent_at' => now(), 'service_type' => getServiceType('APT_NEW')]
                    );
                    
                    //OTP log for testing 
                    Log::info($generateOtp);
    
                    if ($otp) {
                        $action = 'APT_OTP';
                        $data = ['otp' => $generateOtp];
                         //OTP log for testing 
                    Log::info($generateOtp);
                        $communicationService->sendSmsMessage($data, $request->mobile, $action);
                        $communicationService->sendWhatsAppMessage($data, $request->mobile, $action);
                        return response()->json(['success' => true, 'message' => 'OTP sent to mobile number ' . $request->mobile . ' successfully']);
                    }
                } elseif ($latestMobileRecord->is_mobile_verified == '1' && $latestMobileRecord->is_email_verified == '1') {
                    // If both are verified, create a new OTP
                    $generateOtp = GeneralFunctions::generateUniqueRandomNumber(4);
                    $otp = Otp::create([
                        'mobile' => $request->mobile,
                        'mobile_otp' => $generateOtp,
                        'mobile_otp_sent_at' => now(),
                        'service_type' => getServiceType('APT_NEW')
                    ]);
                    

                    if ($otp) {
                        $action = 'APT_OTP';
                        $data = ['otp' => $generateOtp];

                        Log::info($generateOtp);
                        $communicationService->sendSmsMessage(['otp' => $generateOtp], $request->mobile, 'APT_OTP');
                        $communicationService->sendWhatsAppMessage(['otp' => $generateOtp], $request->mobile, 'APT_OTP');
                        return response()->json(['success' => true, 'message' => 'OTP sent to mobile number ' . $request->mobile . ' successfully']);
                    }
                } else {
                    // Either mobile is unverified or email is unverified, update the existing record
                    $generateOtp = GeneralFunctions::generateUniqueRandomNumber(4);
                    $latestMobileRecord->mobile_otp = $generateOtp;
                    $latestMobileRecord->mobile_otp_sent_at = now();
                    if ($latestMobileRecord->save()) {
                        Log::info($generateOtp);
                        $communicationService->sendSmsMessage(['otp' => $generateOtp], $request->mobile, 'APT_OTP');
                        $communicationService->sendWhatsAppMessage(['otp' => $generateOtp], $request->mobile, 'APT_OTP');
                        return response()->json(['success' => true, 'message' => 'OTP sent to mobile number ' . $request->mobile . ' successfully']);
                    }
                }
            } 
    
            // If email is provided in the request
            elseif ($request->has('email')) {
                // Check if mobileToVerify is hidden in the request
                if ($request->has('mobileToVerify')) {
                    // Fetch the latest OTP record for the hidden mobile
                    $latestMobileRecord = Otp::where('mobile', $request->mobileToVerify)
                        ->orderBy('created_at', 'desc')
                        ->first();
    
                    // If the latest record has both email and mobile verified, create a new record for email
                    if ($latestMobileRecord && $latestMobileRecord->is_email_verified == '1' && $latestMobileRecord->is_mobile_verified == '1') {
                        $generateOtp = GeneralFunctions::generateUniqueRandomNumber(4);
                        $otp = Otp::create([
                            'email' => $request->email,
                            'email_otp' => $generateOtp,
                            'email_otp_sent_at' => now(),
                            'service_type' => getServiceType('APT_NEW')
                        ]);
    
                        if ($otp) {
                            $action = 'APT_OTP';
                            $this->settingsService->applyMailSettings($action);
                            $data = ['otp' => $generateOtp];
                            
                            //OTP log for testing 
                            Log::info($generateOtp);
                            
                            Mail::to($request->email)->send(new CommonMail($data, $action));
                            return response()->json(['success' => true, 'message' => 'OTP sent to email ' . $request->email . ' successfully']);
                        }
                    } else if ($latestMobileRecord) {
                        // If only the mobile verification exists, update the same record with email details
                        $generateOtp = GeneralFunctions::generateUniqueRandomNumber(4);
                        $latestMobileRecord->email = $request->email;
                        $latestMobileRecord->email_otp = $generateOtp;
                        $latestMobileRecord->email_otp_sent_at = now();
                        if ($latestMobileRecord->save()) {
                            $action = 'APT_OTP';
                            $this->settingsService->applyMailSettings($action);
                            $data = ['otp' => $generateOtp];
                              
                            //OTP log for testing 
                            Log::info($generateOtp);
                            
                            Mail::to($request->email)->send(new CommonMail($data, $action));
                            return response()->json(['success' => true, 'message' => 'OTP sent to email ' . $request->email . ' successfully']);
                        }
                    }
                }
    
                // Existing logic for the first-time or fallback scenario
                $latestEmailRecord = Otp::where('email', $request->email)
                ->orderBy('created_at', 'desc')
                ->first();
    
                if (!$latestEmailRecord) {
                    // Existing OTP generation and sending process
                    $generateOtp = GeneralFunctions::generateUniqueRandomNumber(4);
                    $action = 'APT_OTP';
                    $this->settingsService->applyMailSettings($action);
                    $data = ['otp' => $generateOtp];
    
                    try {
                        Mail::to($request->email)->send(new CommonMail($data, $action));
                        $otp = Otp::updateOrCreate(
                            ['email' => $request->email],
                            ['email_otp' => $generateOtp, 'email_otp_sent_at' => now(), 'service_type' => getServiceType('APT_NEW')]
                        );
                         //OTP log for testing 
                            Log::info($generateOtp);
    
                        if ($otp) {
                            return response()->json(['success' => true, 'message' => 'OTP sent to email ' . $request->email . ' successfully']);
                        }
                    } catch (TransportExceptionInterface $e) {
                        Log::error('Failed to send email: ' . $e->getMessage());
                        return response()->json(['success' => false, 'message' => 'Failed to send OTP via email.']);
                    }
                } elseif ($latestEmailRecord->is_email_verified == '1' && $latestEmailRecord->is_mobile_verified == '1') {
                    // If both are verified, create a new OTP
                    $generateOtp = GeneralFunctions::generateUniqueRandomNumber(4);
                    $otp = Otp::create([
                        'email' => $request->email,
                        'email_otp' => $generateOtp,
                        'email_otp_sent_at' => now(),
                        'service_type' => getServiceType('APT_NEW')
                    ]);
                    if ($otp) {
                        $action = 'APT_OTP';
                        $data = ['otp' => $generateOtp];
                        Log::info($generateOtp);
                        $this->settingsService->applyMailSettings('APT_OTP');
                        Mail::to($request->email)->send(new CommonMail(['otp' => $generateOtp], 'APT_OTP'));
                        return response()->json(['success' => true, 'message' => 'OTP sent to email ' . $request->email . ' successfully']);
                    }
                } else {
                    // Either mobile is unverified or email is unverified, update the existing record
                    $generateOtp = GeneralFunctions::generateUniqueRandomNumber(4);
                    $latestEmailRecord->email_otp = $generateOtp;
                    $latestEmailRecord->email_otp_sent_at = now();
                    if ($latestEmailRecord->save()) {
                        Log::info($generateOtp);
                        $this->settingsService->applyMailSettings('APT_OTP');
                        Mail::to($request->email)->send(new CommonMail(['otp' => $generateOtp], 'APT_OTP'));
                        return response()->json(['success' => true, 'message' => 'OTP sent to email ' . $request->email . ' successfully']);
                    }
                }
            } 
    
            // No mobile or email provided in the request
            else {
                return response()->json(['success' => false, 'message' => 'No mobile or email provided']);
            }
        } catch (\Exception $e) {
            Log::error('Error in saveOtp: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred. Please try again later.']);
        }
    }
    

    public function verifyAptOtp(Request $request)
    {
        try {
            // Verify Mobile OTP
            if ($request->has('mobileOtp')) {
                Log::info('Verifying mobile OTP for: ' . $request->mobile);
                
                // Fetch the latest OTP record for the mobile number
                $databaseOtp = Otp::where('mobile', $request->mobile)
                    ->orderBy('created_at', 'desc')
                    ->first();
    
                // Check if the OTP record was found and compare the OTP values
                if ($databaseOtp && trim($databaseOtp->mobile_otp) === trim($request->mobileOtp)) {
                    // Check if the record has both email and mobile verified
                    if ($databaseOtp->is_email_verified == '1' && $databaseOtp->is_mobile_verified == '1') {
                        // Create a new OTP record since both were already verified
                        $databaseOtp = Otp::create([
                            'mobile' => $request->mobile,
                            'is_mobile_verified' => '1',
                            'mobile_verified_at' => now(),
                            'mobile_otp' => null,
                            'service_type' => getServiceType('APT_NEW')
                        ]);
                    } else {
                        // Mark the mobile as verified and clear the OTP
                        $databaseOtp->is_mobile_verified = '1';
                        $databaseOtp->mobile_verified_at = now();
                        $databaseOtp->mobile_otp = null;
                    }
    
                    if ($databaseOtp->save()) {
                        return response()->json(['success' => true, 'message' => 'Mobile number ' . $request->mobile . ' verified successfully']);
                    } else {
                        return response()->json(['success' => false, 'message' => 'Mobile number not verified']);
                    }
                } else {
                    Log::warning('Mobile OTP not matched or expired for: ' . $request->mobile);
                    Log::debug('Expected OTP: ' . ($databaseOtp ? $databaseOtp->mobile_otp : 'No OTP found') . ' | Entered OTP: ' . $request->mobileOtp);
                    return response()->json(['success' => false, 'message' => 'OTP not matched or expired. Please enter the correct OTP.']);
                }
            } 
            // Verify Email OTP
            elseif ($request->has('emailOtp')) {
                Log::info('Verifying email OTP for: ' . $request->email);
    
                // Fetch the latest OTP record for the email
                $databaseOtp = Otp::where('email', $request->email)
                    ->orderBy('created_at', 'desc')
                    ->first();
    
                // Check if the OTP record was found and compare the OTP values
                if ($databaseOtp && trim($databaseOtp->email_otp) === trim($request->emailOtp)) {
                    // Check if the record has both email and mobile verified
                    if ($databaseOtp->is_email_verified == '1' && $databaseOtp->is_mobile_verified == '1') {
                        // Create a new OTP record since both were already verified
                        $databaseOtp = Otp::create([
                            'email' => $request->email,
                            'is_email_verified' => '1',
                            'email_verified_at' => now(),
                            'email_otp' => null,
                            'service_type' => getServiceType('APT_NEW')
                        ]);
                    } else {
                        // Mark the email as verified and clear the OTP
                        $databaseOtp->is_email_verified = '1';
                        $databaseOtp->email_verified_at = now();
                        $databaseOtp->email_otp = null;
                    }
    
                    if ($databaseOtp->save()) {
                        return response()->json(['success' => true, 'message' => 'Email ' . $request->email . ' verified successfully']);
                    } else {
                        return response()->json(['success' => false, 'message' => 'Email not verified']);
                    }
                } else {
                    Log::warning('Email OTP not matched or expired for: ' . $request->email);
                    Log::debug('Expected OTP: ' . ($databaseOtp ? $databaseOtp->email_otp : 'No OTP found') . ' | Entered OTP: ' . $request->emailOtp);
                    return response()->json(['success' => false, 'message' => 'OTP not matched or expired. Please enter the correct OTP.']);
                }
            } 
            // No OTP provided in the request
            else {
                return response()->json(['success' => false, 'message' => 'No OTP provided']);
            }
        } catch (\Exception $e) {
            Log::error('Error in verifyOtp: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred. Please try again later.']);
        }
    }

    
}