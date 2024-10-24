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

class OldOtpController extends Controller
{
    protected $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    public function saveAptOtp(Request $request, CommunicationService $communicationService)
    {
        try {
            $startOfWeek = now()->startOfWeek(); // Default start of week is Monday
            $endOfWeek = now()->endOfWeek(); // End of the current week

            // Check if mobile number is provided
            if ($request->has('mobile')) {
                $isMobileAvailable = Otp::where('mobile', $request->mobile)
                    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                    ->where('is_mobile_verified', '1')
                    ->first();

                if (!$isMobileAvailable) {
                    $generateOtp = GeneralFunctions::generateUniqueRandomNumber(4);

                    if ($request->has('emailToVerify')) {
                        $isEmailVerified = Otp::where('email', $request->emailToVerify)
                            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                            ->first();

                        if ($isEmailVerified) {
                            $isEmailVerified->mobile = $request->mobile;
                            $isEmailVerified->mobile_otp = $generateOtp;
                            $isEmailVerified->mobile_otp_sent_at = now();
                            if ($isEmailVerified->save()) {
                                $action = 'APT_OTP';
                                $data = ['otp' => $generateOtp];
                                $communicationService->sendSmsMessage($data, $request->mobile, $action);
                                $communicationService->sendWhatsAppMessage($data, $request->mobile, $action);
                                return response()->json(['success' => true, 'message' => 'OTP sent to mobile number ' . $request->mobile . ' successfully']);
                            } else {
                                return response()->json(['success' => false, 'message' => 'Failed to send OTP']);
                            }
                        }
                    }

                    $otp = Otp::updateOrCreate(
                        ['mobile' => $request->mobile],
                        ['mobile_otp' => $generateOtp, 'mobile_otp_sent_at' => now(), 'service_type' => getServiceType('APT_NEW')]
                    );

                    if ($otp) {
                        $action = 'APT_OTP';
                        $data = ['otp' => $generateOtp];
                        $communicationService->sendSmsMessage($data, $request->mobile, $action);
                        $communicationService->sendWhatsAppMessage($data, $request->mobile, $action);
                        return response()->json(['success' => true, 'message' => 'OTP sent to mobile number ' . $request->mobile . ' successfully']);
                    } else {
                        return response()->json(['success' => false, 'message' => 'Failed to send OTP']);
                    }
                } else {
                    return response()->json(['success' => false, 'message' => 'You have already booked an appointment in this week. Please book in the next available week.']);
                }
            } elseif ($request->has('email')) {
                $isEmailAvailable = Otp::where('email', $request->email)
                    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                    ->where('is_email_verified', '1')
                    ->first();

                if (!$isEmailAvailable) {
                    $generateOtp = GeneralFunctions::generateUniqueRandomNumber(4);

                    $action = 'APT_OTP';
                    $this->settingsService->applyMailSettings($action);
                    $data = ['otp' => $generateOtp];

                    try {
                        Mail::to($request->email)->send(new CommonMail($data, $action));

                        if ($request->has('mobileToVerify')) {
                            $isMobileVerified = Otp::where('mobile', $request->mobileToVerify)
                                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                                ->first();

                            if ($isMobileVerified) {
                                $isMobileVerified->email = $request->email;
                                $isMobileVerified->email_otp = $generateOtp;
                                $isMobileVerified->email_otp_sent_at = now();
                                $isMobileVerified->save();
                                return response()->json(['success' => true, 'message' => 'OTP sent to email ' . $request->email . ' successfully']);
                            }
                        }

                        $otp = Otp::updateOrCreate(
                            ['email' => $request->email],
                            ['email_otp' => $generateOtp, 'email_otp_sent_at' => now(), 'service_type' => getServiceType('APT_NEW')]
                        );

                        if ($otp) {
                            return response()->json(['success' => true, 'message' => 'OTP sent to email ' . $request->email . ' successfully']);
                        } else {
                            return response()->json(['success' => false, 'message' => 'Failed to send OTP']);
                        }
                    } catch (TransportExceptionInterface $e) {
                        Log::error('Failed to send email: ' . $e->getMessage());
                        return response()->json(['success' => false, 'message' => 'Failed to send OTP via email.']);
                    }
                } else {
                    return response()->json(['success' => false, 'message' => 'You have already booked an appointment in this week. Please book in the next available week.']);
                }
            } else {
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
            $startOfWeek = now()->startOfWeek();
            $endOfWeek = now()->endOfWeek();

            if ($request->has('mobileOtp')) {
                Log::info('Verifying mobile OTP for: ' . $request->mobile);
                $databaseOtp = Otp::where('mobile', $request->mobile)
                    ->where('mobile_otp', $request->mobileOtp)
                    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                    ->first();

                if ($databaseOtp) {
                    $databaseOtp->is_mobile_verified = '1';
                    $databaseOtp->mobile_verified_at = now(); // Set the mobile verified timestamp
                    $databaseOtp->mobile_otp = null;
                    if ($databaseOtp->save()) {
                        return response()->json(['success' => true, 'message' => 'Mobile number ' . $request->mobile . ' verified successfully']);
                    } else {
                        return response()->json(['success' => false, 'message' => 'Mobile number not verified']);
                    }
                } else {
                    Log::warning('Mobile OTP not matched or expired for: ' . $request->mobile);
                    return response()->json(['success' => false, 'message' => 'OTP not matched or expired. Please enter the correct OTP.']);
                }
            } elseif ($request->has('emailOtp')) {
                $databaseOtp = Otp::where('email', $request->email)
                    ->where('email_otp', $request->emailOtp)
                    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                    ->first();

                if ($databaseOtp) {
                    $databaseOtp->is_email_verified = '1';
                    $databaseOtp->email_verified_at = now(); // Set the email verified timestamp
                    $databaseOtp->email_otp = null;
                    if ($databaseOtp->save()) {
                        return response()->json(['success' => true, 'message' => 'Email ' . $request->email . ' verified successfully']);
                    } else {
                        return response()->json(['success' => false, 'message' => 'Email not verified']);
                    }
                } else {
                    return response()->json(['success' => false, 'message' => 'OTP not matched or expired. Please enter the correct OTP.']);
                }
            } else {
                return response()->json(['success' => false, 'message' => 'No OTP provided']);
            }
        } catch (\Exception $e) {
            Log::error('Error in verifyOtp: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred. Please try again later.']);
        }
    }

}
