<?php
require_once(__DIR__ . '/session_config.php');
include('dbconnect.php');


class addTreat
{

    private $text1, $text2, $text3, $text4, $vedana, $poorva, $chikitsa, $kautumbika, $marital, $vayaktika, $tobacco, $smok, $alco, $krodha, $shoka, $chittogvega, $atichintana, $vegadharana, $shift, $udheerna, $frequent, $nidra, $agani, $koshtas, $labour, $field_work, $sedantary, $intellectual, $job_Stress, $menarche, $regular, $irregular, $painful, $scanty,
        $clots, $day, $gravida, $parity, $abortion, $ftnd, $lscs, $fdhd, $general, $pulse, $heart, $resp, $temp, $bp, $spo2, $mutra, $mala, $upalepita, $ragayukta, $jivaothers, $paka, $cc, $shabda, $sparsha, $ruksha, $snigdha, $tanu, $sthula, $ushna, $pale, $conjested, $kalushita, $akshimalasrava, $jalasrava, $raga, $praktuti_type, $praktuti_assement, $saara_type,
        $saara_assement, $smhanana_type, $smhanana_assement, $pramana_type, $pramana_assement, $satmya_type, $satmya_assement, $satwa_type, $satwa_assement, $aharashakti_type, $aharashakti_assement, $vyayama_shakti_type, $vyayama_shakti_assement, $vayah_type, $vayah_assement, $manah, $buddhi, $sanjna, $jnana, $bhakti, $sheela, $chesta, $achara, $smriti,
        $storus1, $type1, $storus2, $type2, $storus3, $type3, $csv, $local_exam, $rogatype, $rs, $pa, $cns, $power, $reflexes, $tone, $others, $gus, $mss, $skin, $akriti, $nindana, $poorvapoorva, $roopa, $upashaya, $dosha, $dushy, $agni, $sroto, $lakshana, $vyadhi, $sanchara, $vyaktasthana, $vyadhiadhisthana, $rogamarga, $rogabala, $rogibala, $rogaswabhava, $upadrava,
        $arista, $samprapti, $sapeksha, $nidana, $vyadi, $pid, $ama, $srotas, $omedicine, $omedicine_name, $roga, $qty, $total1, $morning, $afternoon, $evening, $night, $anupana, $diet, $medicin, $progress, $pdate, $subprocedure, $procedure, $ip_no, $doctor, $ottype, $otdate, $otprocedure, $otcomments;
    private $rr, $chest_shape, $r_nose, $r_throat, $r_cyanosis, $others_a, $resp_moment, $mediastinum, $tenderness, $others_b, $resonant, $cardiac_dullness, $liver_dullness, $other_dullness, $hyper, $a_breath_sounds, $broncho, $breath_sounds, $added_sounds, $pr, $rythom, $volume, $character, $bp2, $ccf, $tender, $pedal, $other_clubing, $c_cyanosis, $c_anemia,
        $scalp, $eyes, $nose, $oral_cavity, $tounge, $neck, $nails, $edema, $lymph, $anemia, $cyanosis, $jaundice, $pigmentation, $wh, $built, $respiration, $chest_wall, $apex_beat, $pulsation, $sound1, $sound2, $liver_cons, $liver_margin, $speech, $gait, $liver_size, $liver_tender, $heart_char, $heart_timing, $beat_loaction,
        $charecter, $tender_areas, $thrills, $diastolic, $dull_loaction, $dull_charecter, $splitting, $a_added_sounds, $site, $other_location, $charecter2, $tender_areas2, $spleen_size, $spleen_tender, $pitch, $grade,
        $present, $absent, $other_heart, $lips, $teeth, $g_toungue, $tonsils, $shape, $g_respiration, $umbilicus, $hernial, $vessels, $striae, $p_tenderness, $rigidity, $rbs, $urine_output, $respiratory_rate, $nadi,
        $spleen_margin, $spleen_cons, $swelling, $rebound, $precussion, $dullness_grade1, $dullness_grade2, $dullness_grade3, $dullness_grade, $peristalitic, $rubs, $rectal, $mental_status, $memory, $orientation, $behavior, $nerve_exam, $nutrition, $muscle_score, $muscle_tone, $coordinations, $involuntary, $touch, $pain, $sup_temp, $crude_touch, $vaibration, $joint_sense, $tactile_l, $tactile_d, $tactile_e, $tactile_o, $signs1, $signs2, $signs3, $jerks1, $jerks2, $jerks3, $jerks4, $abdominal, $plantar, $jaw_jerk, $knee_reflex, $ankle_reflex, $any_other;
    function settext1($text1)
    {
        $this->text1 = $text1;
    }
    function settext2($text2)
    {
        $this->text2 = $text2;
    }
    function settext3($text3)
    {
        $this->text3 = $text3;
    }
    function settext4($text4)
    {
        $this->text4 = $text4;
    }
    function setvedana($vedana)
    {
        $this->vedana = $vedana;
    }
    function setpoorva($poorva)
    {
        $this->poorva = $poorva;
    }
    function setchikitsa($chikitsa)
    {
        $this->chikitsa = $chikitsa;
    }
    function setkautumbika($kautumbika)
    {
        $this->kautumbika = $kautumbika;
    }
    function setmarital($marital)
    {
        $this->marital = $marital;
    }
    function setvayaktika($vayaktika)
    {
        $this->vayaktika = $vayaktika;
    }
    function settobacco($tobacco)
    {
        $this->tobacco = $tobacco;
    }
    function setsmok($smok)
    {
        $this->smok = $smok;
    }
    function setalco($alco)
    {
        $this->alco = $alco;
    }
    function setkrodha($krodha)
    {
        $this->krodha = $krodha;
    }
    function setshoka($shoka)
    {
        $this->shoka = $shoka;
    }
    function setchittogvega($chittogvega)
    {
        $this->chittogvega = $chittogvega;
    }
    function setatichintana($atichintana)
    {
        $this->atichintana = $atichintana;
    }
    function setvegadharana($vegadharana)
    {
        $this->vegadharana = $vegadharana;
    }
    function setshift($shift)
    {
        $this->shift = $shift;
    }
    function setudheerna($udheerna)
    {
        $this->udheerna = $udheerna;
    }
    function setfrequent($frequent)
    {
        $this->frequent = $frequent;
    }
    function setnidra($nidra)
    {
        $this->nidra = $nidra;
    }
    function setagani($agani)
    {
        $this->agani = $agani;
    }
    function setkoshtas($koshtas)
    {
        $this->koshtas = $koshtas;
    }
    function setlabour($labour)
    {
        $this->labour = $labour;
    }
    function setfield_work($field_work)
    {
        $this->field_work = $field_work;
    }
    function setsedantary($sedantary)
    {
        $this->sedantary = $sedantary;
    }
    function setintellectual($intellectual)
    {
        $this->intellectual = $intellectual;
    }
    function setjob_Stress($job_Stress)
    {
        $this->job_Stress = $job_Stress;
    }
    function setmenarche($menarche)
    {
        $this->menarche = $menarche;
    }
    function setregular($regular)
    {
        $this->regular = $regular;
    }
    function setirregular($irregular)
    {
        $this->irregular = $irregular;
    }
    function setpainful($painful)
    {
        $this->painful = $painful;
    }
    function setscanty($scanty)
    {
        $this->scanty = $scanty;
    }
    function setclots($clots)
    {
        $this->clots = $clots;
    }
    function setday($day)
    {
        $this->day = $day;
    }
    function setgravida($gravida)
    {
        $this->gravida = $gravida;
    }
    function setparity($parity)
    {
        $this->parity = $parity;
    }
    function setabortion($abortion)
    {
        $this->abortion = $abortion;
    }
    function setftnd($ftnd)
    {
        $this->ftnd = $ftnd;
    }
    function setlscs($lscs)
    {
        $this->lscs = $lscs;
    }
    function setfdhd($fdhd)
    {
        $this->fdhd = $fdhd;
    }




    function setgeneral($general)
    {
        $this->general = $general;
    }
    function setpulse($pulse)
    {
        $this->pulse = $pulse;
    }
    function setheart($heart)
    {
        $this->heart = $heart;
    }
    function setresp($resp)
    {
        $this->resp = $resp;
    }
    function settemp($temp)
    {
        $this->temp = $temp;
    }
    function setbp($bp)
    {
        $this->bp = $bp;
    }
    function setspo2($spo2)
    {
        $this->spo2 = $spo2;
    }
    function setnadi($nadi)
    {
        $this->nadi = $nadi;
    }
    function setmutra($mutra)
    {
        $this->mutra = $mutra;
    }
    function setmala($mala)
    {
        $this->mala = $mala;
    }
    function setupalepita($upalepita)
    {
        $this->upalepita = $upalepita;
    }
    function setragayukta($ragayukta)
    {
        $this->ragayukta = $ragayukta;
    }
    function setjivaothers($jivaothers)
    {
        $this->jivaothers = $jivaothers;
    }
    function setpaka($paka)
    {
        $this->paka = $paka;
    }
    function setcc($cc)
    {
        $this->cc = $cc;
    }
    function setshabda($shabda)
    {
        $this->shabda = $shabda;
    }
    function setsparsha($sparsha)
    {
        $this->sparsha = $sparsha;
    }
    function setruksha($ruksha)
    {
        $this->ruksha = $ruksha;
    }
    function setsnigdha($snigdha)
    {
        $this->snigdha = $snigdha;
    }
    function settanu($tanu)
    {
        $this->tanu = $tanu;
    }
    function setsthula($sthula)
    {
        $this->sthula = $sthula;
    }
    function setushna($ushna)
    {
        $this->ushna = $ushna;
    }
    function setpale($pale)
    {
        $this->pale = $pale;
    }
    function setconjested($conjested)
    {
        $this->conjested = $conjested;
    }
    function setkalushita($kalushita)
    {
        $this->kalushita = $kalushita;
    }
    function setakshimalasrava($akshimalasrava)
    {
        $this->akshimalasrava = $akshimalasrava;
    }
    function setjalasrava($jalasrava)
    {
        $this->jalasrava = $jalasrava;
    }
    function setraga($raga)
    {
        $this->raga = $raga;
    }

    function setpraktuti_type($praktuti_type)
    {
        $this->praktuti_type = $praktuti_type;
    }
    function setpraktuti_assement($praktuti_assement)
    {
        $this->praktuti_assement = $praktuti_assement;
    }
    function setsaara_type($saara_type)
    {
        $this->saara_type = $saara_type;
    }
    function setsaara_assement($saara_assement)
    {
        $this->saara_assement = $saara_assement;
    }
    function setsmhanana_type($smhanana_type)
    {
        $this->smhanana_type = $smhanana_type;
    }
    function setsmhanana_assement($smhanana_assement)
    {
        $this->smhanana_assement = $smhanana_assement;
    }
    function setpramana_type($pramana_type)
    {
        $this->pramana_type = $pramana_type;
    }
    function setpramana_assement($pramana_assement)
    {
        $this->pramana_assement = $pramana_assement;
    }
    function setsatmya_type($satmya_type)
    {
        $this->satmya_type = $satmya_type;
    }
    function setsatmya_assement($satmya_assement)
    {
        $this->satmya_assement = $satmya_assement;
    }
    function setsatwa_type($satwa_type)
    {
        $this->satwa_type = $satwa_type;
    }
    function setsatwa_assement($satwa_assement)
    {
        $this->satwa_assement = $satwa_assement;
    }
    function setaharashakti_type($aharashakti_type)
    {
        $this->aharashakti_type = $aharashakti_type;
    }
    function setaharashakti_assement($aharashakti_assement)
    {
        $this->aharashakti_assement = $aharashakti_assement;
    }
    function setvyayama_shakti_type($vyayama_shakti_type)
    {
        $this->vyayama_shakti_type = $vyayama_shakti_type;
    }
    function setvyayama_shakti_assement($vyayama_shakti_assement)
    {
        $this->vyayama_shakti_assement = $vyayama_shakti_assement;
    }
    function setvayah_type($vayah_type)
    {
        $this->vayah_type = $vayah_type;
    }
    function setvayah_assement($vayah_assement)
    {
        $this->vayah_assement = $vayah_assement;
    }
    function setmanah($manah)
    {
        $this->manah = $manah;
    }
    function setbuddhi($buddhi)
    {
        $this->buddhi = $buddhi;
    }
    function setsanjna($sanjna)
    {
        $this->sanjna = $sanjna;
    }
    function setjnana($jnana)
    {
        $this->jnana = $jnana;
    }
    function setbhakti($bhakti)
    {
        $this->bhakti = $bhakti;
    }
    function setsheela($sheela)
    {
        $this->sheela = $sheela;
    }
    function setchesta($chesta)
    {
        $this->chesta = $chesta;
    }
    function setachara($achara)
    {
        $this->achara = $achara;
    }
    function setsmriti($smriti)
    {
        $this->smriti = $smriti;
    }
    function setstorus1($storus1)
    {
        $this->storus1 = $storus1;
    }
    function settype1($type1)
    {
        $this->type1 = $type1;
    }
    function setstorus2($storus2)
    {
        $this->storus2 = $storus2;
    }
    function settype2($type2)
    {
        $this->type2 = $type2;
    }
    function setstorus3($storus3)
    {
        $this->storus3 = $storus3;
    }
    function settype3($type3)
    {
        $this->type3 = $type3;
    }
    function  setcsv($csv)
    {
        $this->csv = $csv;
    }
    function setlocal_exam($local_exam)
    {
        $this->local_exam = $local_exam;
    }
    function setrogatype($rogatype)
    {
        $this->rogatype = $rogatype;
    }
    function setrs($rs)
    {
        $this->rs = $rs;
    }
    function setpa($pa)
    {
        $this->pa = $pa;
    }
    function setcns($cns)
    {
        $this->cns = $cns;
    }
    function setpower($power)
    {
        $this->power = $power;
    }
    function setreflexes($reflexes)
    {
        $this->reflexes = $reflexes;
    }
    function settone($tone)
    {
        $this->tone = $tone;
    }
    function setothers($others)
    {
        $this->others = $others;
    }
    function setgus($gus)
    {
        $this->gus = $gus;
    }
    function setmss($mss)
    {
        $this->mss = $mss;
    }
    function setskin($skin)
    {
        $this->skin = $skin;
    }
    function setakriti($akriti)
    {
        $this->akriti = $akriti;
    }
    function setnindana($nindana)
    {
        $this->nindana = $nindana;
    }
    function setpoorvapoorva($poorvapoorva)
    {
        $this->poorvapoorva = $poorvapoorva;
    }
    function setroopa($roopa)
    {
        $this->roopa = $roopa;
    }
    function setupashaya($upashaya)
    {
        $this->upashaya = $upashaya;
    }
    function setdosha($dosha)
    {
        $this->dosha = $dosha;
    }
    function setdushy($dushy)
    {
        $this->dushy = $dushy;
    }
    function setagni($agni)
    {
        $this->agni = $agni;
    }
    function setsroto($sroto)
    {
        $this->sroto = $sroto;
    }
    function setlakshana($lakshana)
    {
        $this->lakshana = $lakshana;
    }
    function setvyadhi($vyadhi)
    {
        $this->vyadhi = $vyadhi;
    }
    function setsanchara($sanchara)
    {
        $this->sanchara = $sanchara;
    }
    function setvyaktasthana($vyaktasthana)
    {
        $this->vyaktasthana = $vyaktasthana;
    }
    function setvyadhiadhisthana($vyadhiadhisthana)
    {
        $this->vyadhiadhisthana = $vyadhiadhisthana;
    }
    function setrogamarga($rogamarga)
    {
        $this->rogamarga = $rogamarga;
    }
    function setrogabala($rogabala)
    {
        $this->rogabala = $rogabala;
    }
    function setrogibala($rogibala)
    {
        $this->rogibala = $rogibala;
    }
    function setrogaswabhava($rogaswabhava)
    {
        $this->rogaswabhava = $rogaswabhava;
    }
    function setupadrava($upadrava)
    {
        $this->upadrava = $upadrava;
    }
    function setarista($arista)
    {
        $this->arista = $arista;
    }
    function setsamprapti($samprapti)
    {
        $this->samprapti = $samprapti;
    }
    function setsapeksha($sapeksha)
    {
        $this->sapeksha = $sapeksha;
    }
    function setnidana($nidana)
    {
        $this->nidana = $nidana;
    }
    function setvyadi($vyadi)
    {
        $this->vyadi = $vyadi;
    }
    function setpid($pid)
    {
        $this->pid = $pid;
    }
    function setama($ama)
    {
        $this->ama = $ama;
    }
    function setsrotas($srotas)
    {
        $this->srotas = $srotas;
    }
    function setomedicine($omedicine)
    {
        $this->omedicine = $omedicine;
    }
    function setomedicine_name($omedicine_name)
    {
        $this->omedicine_name = $omedicine_name;
    }
    function setroga($roga)
    {
        $this->roga = $roga;
    }
    function setqty($qty)
    {
        $this->qty = $qty;
    }
    function settotal1($total1)
    {
        $this->total1 = $total1;
    }
    function setmorning($morning)
    {
        $this->morning = $morning;
    }
    function setafternoon($afternoon)
    {
        $this->afternoon = $afternoon;
    }
    function setevening($evening)
    {
        $this->evening = $evening;
    }
    function setnight($night)
    {
        $this->night = $night;
    }
    function setanupana($anupana)
    {
        $this->anupana = $anupana;
    }

    function setdiet($diet)
    {
        $this->diet = $diet;
    }

    function setmedicin($medicin)
    {
        $this->medicin = $medicin;
    }

    function setprogress($progress)
    {
        $this->progress = $progress;
    }


    function setpdate($pdate)
    {
        $this->pdate = $pdate;
    }

    function setsubprocedure($subprocedure)
    {
        $this->subprocedure = $subprocedure;
    }

    function setprocedure($procedure)
    {
        $this->procedure = $procedure;
    }

    function setip_no($ip_no)
    {
        $this->ip_no = $ip_no;
    }


    function setdoctor($doctor)
    {
        $this->doctor = $doctor;
    }

    function setottype($ottype)
    {
        $this->ottype = $ottype;
    }

    function setotdate($otdate)
    {
        $this->otdate = $otdate;
    }

    function setotprocedure($otprocedure)
    {
        $this->otprocedure = $otprocedure;
    }

    function setotcomments($otcomments)
    {
        $this->otcomments = $otcomments;
    }













    // function setpulse($pulse)
    // {
    //     $this->pulse=$pulse;
    // }
    function setrespiration($respiration)
    {
        $this->respiration = $respiration;
    }
    // function settemp($temp)
    // {
    //     $this->temp=$temp;
    // }
    // function setbp($bp)
    // {
    //     $this->bp=$bp;
    // }
    function setwh($wh)
    {
        $this->wh = $wh;
    }
    function setbuilt($built)
    {
        $this->built = $built;
    }
    // function setbp($bp)
    // {
    //     $this->bp=$bp;
    // }
    function setscalp($scalp)
    {
        $this->scalp = $scalp;
    }
    function seteyes($eyes)
    {
        $this->eyes = $eyes;
    }
    function setnose($nose)
    {
        $this->nose = $nose;
    }
    function setoral_cavity($oral_cavity)
    {
        $this->oral_cavity = $oral_cavity;
    }
    function settounge($tounge)
    {
        $this->tounge = $tounge;
    }
    function setneck($neck)
    {
        $this->neck = $neck;
    }
    function setnails($nails)
    {
        $this->nails = $nails;
    }
    function setedema($edema)
    {
        $this->edema = $edema;
    }
    function setlymph($lymph)
    {
        $this->lymph = $lymph;
    }
    function setanemia($anemia)
    {
        $this->anemia = $anemia;
    }
    function setcyanosis($cyanosis)
    {
        $this->cyanosis = $cyanosis;
    }
    function setjaundice($jaundice)
    {
        $this->jaundice = $jaundice;
    }
    function setpigmentation($pigmentation)
    {
        $this->pigmentation = $pigmentation;
    }
    // function setothers($others)
    // {
    //     $this->others=$others;
    // }

    function setrr($rr)
    {
        $this->rr = $rr;
    }
    function setchest_shape($chest_shape)
    {
        $this->chest_shape = $chest_shape;
    }
    function setr_nose($r_nose)
    {
        $this->r_nose = $r_nose;
    }
    function setr_throat($r_throat)
    {
        $this->r_throat = $r_throat;
    }
    function setr_cyanosis($r_cyanosis)
    {
        $this->r_cyanosis = $r_cyanosis;
    }
    function setothers_a($others_a)
    {
        $this->others_a = $others_a;
    }
    function setresp_moment($resp_moment)
    {
        $this->resp_moment = $resp_moment;
    }
    function setmediastinum($mediastinum)
    {
        $this->mediastinum = $mediastinum;
    }
    function settenderness($tenderness)
    {
        $this->tenderness = $tenderness;
    }
    function setothers_b($others_b)
    {
        $this->others_b = $others_b;
    }

    function setresonant($resonant)
    {
        $this->resonant = $resonant;
    }
    function setcardiac_dullness($cardiac_dullness)
    {
        $this->cardiac_dullness = $cardiac_dullness;
    }
    function setliver_dullness($liver_dullness)
    {
        $this->liver_dullness = $liver_dullness;
    }
    function setother_dullness($other_dullness)
    {
        $this->other_dullness = $other_dullness;
    }
    function sethyper($hyper)
    {
        $this->hyper = $hyper;
    }
    function seta_breath_sounds($a_breath_sounds)
    {
        $this->a_breath_sounds = $a_breath_sounds;
    }
    function setbroncho($broncho)
    {
        $this->broncho = $broncho;
    }
    function setbreath_sounds($breath_sounds)
    {
        $this->breath_sounds = $breath_sounds;
    }
    function setadded_sounds($added_sounds)
    {
        $this->added_sounds = $added_sounds;
    }
    function setpr($pr)
    {
        $this->pr = $pr;
    }
    function setrythom($rythom)
    {
        $this->rythom = $rythom;
    }
    function setvolume($volume)
    {
        $this->volume = $volume;
    }
    function setcharacter($character)
    {
        $this->character = $character;
    }
    function setbp2($bp2)
    {
        $this->bp2 = $bp2;
    }
    function setccf($ccf)
    {
        $this->ccf = $ccf;
    }
    function settender($tender)
    {
        $this->tender = $tender;
    }
    function setpedal($pedal)
    {
        $this->pedal = $pedal;
    }
    function setother_clubing($other_clubing)
    {
        $this->other_clubing = $other_clubing;
    }
    function setc_cyanosis($c_cyanosis)
    {
        $this->c_cyanosis = $c_cyanosis;
    }
    function setc_anemia($c_anemia)
    {
        $this->c_anemia = $c_anemia;
    }



    function setchest_wall($chest_wall)
    {
        $this->chest_wall = $chest_wall;
    }
    function setapex_beat($apex_beat)
    {
        $this->apex_beat = $apex_beat;
    }
    function setpulsation($pulsation)
    {
        $this->pulsation = $pulsation;
    }
    function setbeat_loaction($beat_loaction)
    {
        $this->beat_loaction = $beat_loaction;
    }
    function setcharecter($charecter)
    {
        $this->charecter = $charecter;
    }
    function settender_areas($tender_areas)
    {
        $this->tender_areas = $tender_areas;
    }
    function setother_location($other_location)
    {
        $this->other_location = $other_location;
    }
    function setcharecter2($charecter2)
    {
        $this->charecter2 = $charecter2;
    }
    function settender_areas2($tender_areas2)
    {
        $this->tender_areas2 = $tender_areas2;
    }
    function setthrills($thrills)
    {
        $this->thrills = $thrills;
    }
    function setdiastolic($diastolic)
    {
        $this->diastolic = $diastolic;
    }
    function setdull_loaction($dull_loaction)
    {
        $this->dull_loaction = $dull_loaction;
    }
    function setdull_charecter($dull_charecter)
    {
        $this->dull_charecter = $dull_charecter;
    }
    function setsound1($sound1)
    {
        $this->sound1 = $sound1;
    }
    function setsound2($sound2)
    {
        $this->sound2 = $sound2;
    }
    function setsplitting($splitting)
    {
        $this->splitting = $splitting;
    }
    function seta_added_sounds($a_added_sounds)
    {
        $this->a_added_sounds = $a_added_sounds;
    }
    function setsite($site)
    {
        $this->site = $site;
    }
    function setheart_timing($heart_timing)
    {
        $this->heart_timing = $heart_timing;
    }
    function setheart_char($heart_char)
    {
        $this->heart_char = $heart_char;
    }
    function setpitch($pitch)
    {
        $this->pitch = $pitch;
    }
    function setgrade($grade)
    {
        $this->grade = $grade;
    }
    function setpresent($present)
    {
        $this->present = $present;
    }
    function setabsent($absent)
    {
        $this->absent = $absent;
    }
    function setother_heart($other_heart)
    {
        $this->other_heart = $other_heart;
    }

    function setlips($lips)
    {
        $this->lips = $lips;
    }
    function setteeth($teeth)
    {
        $this->teeth = $teeth;
    }
    function setg_toungue($g_toungue)
    {
        $this->g_toungue = $g_toungue;
    }
    function settonsils($tonsils)
    {
        $this->tonsils = $tonsils;
    }
    function setshape($shape)
    {
        $this->shape = $shape;
    }
    function setg_respiration($g_respiration)
    {
        $this->g_respiration = $g_respiration;
    }
    function setumbilicus($umbilicus)
    {
        $this->umbilicus = $umbilicus;
    }
    function sethernial($hernial)
    {
        $this->hernial = $hernial;
    }
    function setvessels($vessels)
    {
        $this->vessels = $vessels;
    }
    function setstriae($striae)
    {
        $this->striae = $striae;
    }
    function setp_tenderness($p_tenderness)
    {
        $this->p_tenderness = $p_tenderness;
    }
    function setrigidity($rigidity)
    {
        $this->rigidity = $rigidity;
    }


    function setliver_size($liver_size)
    {
        $this->liver_size = $liver_size;
    }
    function setliver_tender($liver_tender)
    {
        $this->liver_tender = $liver_tender;
    }
    function setliver_margin($liver_margin)
    {
        $this->liver_margin = $liver_margin;
    }
    function setliver_cons($liver_cons)
    {
        $this->liver_cons = $liver_cons;
    }
    function setspleen_size($spleen_size)
    {
        $this->spleen_size = $spleen_size;
    }
    function setspleen_tender($spleen_tender)
    {
        $this->spleen_tender = $spleen_tender;
    }
    function setspleen_margin($spleen_margin)
    {
        $this->spleen_margin = $spleen_margin;
    }
    function setspleen_cons($spleen_cons)
    {
        $this->spleen_cons = $spleen_cons;
    }
    function setswelling($swelling)
    {
        $this->swelling = $swelling;
    }
    function setrebound($rebound)
    {
        $this->rebound = $rebound;
    }
    function setprecussion($precussion)
    {
        $this->precussion = $precussion;
    }

    function setdullness_grade1($dullness_grade1)
    {
        $this->dullness_grade1 = $dullness_grade1;
    }
    function setdullness_grade2($dullness_grade2)
    {
        $this->dullness_grade2 = $dullness_grade2;
    }
    function setdullness_grade3($dullness_grade3)
    {
        $this->dullness_grade3 = $dullness_grade3;
    }
    function setdullness_grade($dullness_grade)
    {
        $this->dullness_grade = $dullness_grade;
    }
    function setperistalitic($peristalitic)
    {
        $this->peristalitic = $peristalitic;
    }
    function setrubs($rubs)
    {
        $this->rubs = $rubs;
    }
    function setrectal($rectal)
    {
        $this->rectal = $rectal;
    }

    function setmental_status($mental_status)
    {
        $this->mental_status = $mental_status;
    }
    function setmemory($memory)
    {
        $this->memory = $memory;
    }
    function setorientation($orientation)
    {
        $this->orientation = $orientation;
    }
    function setspeech($speech)
    {
        $this->speech = $speech;
    }
    function setbehavior($behavior)
    {
        $this->behavior = $behavior;
    }
    function setgait($gait)
    {
        $this->gait = $gait;
    }
    function setnerve_exam($nerve_exam)
    {
        $this->nerve_exam = $nerve_exam;
    }
    function setnutrition($nutrition)
    {
        $this->nutrition = $nutrition;
    }
    function setmuscle_score($muscle_score)
    {
        $this->muscle_score = $muscle_score;
    }
    function setmuscle_tone($muscle_tone)
    {
        $this->muscle_tone = $muscle_tone;
    }
    function setcoordinations($coordinations)
    {
        $this->coordinations = $coordinations;
    }
    function setinvoluntary($involuntary)
    {
        $this->involuntary = $involuntary;
    }
    function settouch($touch)
    {
        $this->touch = $touch;
    }
    function setpain($pain)
    {
        $this->pain = $pain;
    }
    function setsup_temp($sup_temp)
    {
        $this->sup_temp = $sup_temp;
    }
    function setcrude_touch($crude_touch)
    {
        $this->crude_touch = $crude_touch;
    }
    function setvaibration($vaibration)
    {
        $this->vaibration = $vaibration;
    }
    function setjoint_sense($joint_sense)
    {
        $this->joint_sense = $joint_sense;
    }
    function settactile_l($tactile_l)
    {
        $this->tactile_l = $tactile_l;
    }
    function settactile_d($tactile_d)
    {
        $this->tactile_d = $tactile_d;
    }
    function settactile_e($tactile_e)
    {
        $this->tactile_e = $tactile_e;
    }
    function settactile_o($tactile_o)
    {
        $this->tactile_o = $tactile_o;
    }

    function setsigns1($signs1)
    {
        $this->signs1 = $signs1;
    }
    function setsigns2($signs2)
    {
        $this->signs2 = $signs2;
    }
    function setsigns3($signs3)
    {
        $this->signs3 = $signs3;
    }
    function setjerks1($jerks1)
    {
        $this->jerks1 = $jerks1;
    }
    function setjerks2($jerks2)
    {
        $this->jerks2 = $jerks2;
    }
    function setjerks3($jerks3)
    {
        $this->jerks3 = $jerks3;
    }
    function setjerks4($jerks4)
    {
        $this->jerks4 = $jerks4;
    }
    function setabdominal($abdominal)
    {
        $this->abdominal = $abdominal;
    }
    function setplantar($plantar)
    {
        $this->plantar = $plantar;
    }
    function setjaw_jerk($jaw_jerk)
    {
        $this->jaw_jerk = $jaw_jerk;
    }
    function setknee_reflex($knee_reflex)
    {
        $this->knee_reflex = $knee_reflex;
    }
    function setankle_reflex($ankle_reflex)
    {
        $this->ankle_reflex = $ankle_reflex;
    }
    function setany_other($any_other)
    {
        $this->any_other = $any_other;
    }
    function setrbs($rbs)
    {
        $this->rbs = $rbs;
    }
    function seturine_output($urine_output)
    {
        $this->urine_output = $urine_output;
    }
    function setrespiratory_rate($respiratory_rate)
    {
        $this->respiratory_rate = $respiratory_rate;
    }
    function getIcdCodes()
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT * FROM `icd_codes` ";
        $res = mysqli_query($con, $sql);
        return $res;
    }

    function getProce()
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT * FROM `procedure` WHERE `status`='active'";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    function getRoga()
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT * FROM `roga` WHERE `status`='active'";
        $res = mysqli_query($con, $sql);
        return $res;
    }

    function insertHistory($pid)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "INSERT INTO `patient_history`(`patient_id`,`sign_symptoms1`, `sign_symptoms2`, `sign_symptoms3`, `sign_symptoms4`, `present_illness`, `past_illness`, `old_treatment`, `family_history`, `marital`, `food`, `tobacco_chewing`, `smoking`, `alcohol`, `krodha`, `shoka`, `chittogvega`, `atichintana`, `vegadharana`, `udheerna`, `shift_duties`, `frequent_journey`, `nidra`, `agani`, `koshtas`, `menarche`, `day_cycle`, `labour`, `field_work`, `sedantary`, `intellectual`, `job_stress`, `period_regular`, `period_irregular`, `period_painful`, `period_scanty`, `period_withclot`, `gravida`, `parity`, `abortion`, `ftnd`, `lscs`, `fthd`) 
    VALUES ('$pid','$this->text1','$this->text2','$this->text3','$this->text4','$this->vedana','$this->poorva','$this->chikitsa','$this->kautumbika','$this->marital','$this->vayaktika','$this->tobacco','$this->smok','$this->alco','$this->krodha','$this->shoka','$this->chittogvega','$this->atichintana','$this->vegadharana','$this->udheerna','$this->shift','$this->frequent','$this->nidra','$this->agani','$this->koshtas','$this->menarche','$this->day','$this->labour','$this->field_work','$this->sedantary','$this->intellectual','$this->job_Stress','$this->regular','$this->irregular','$this->painful','$this->scanty','$this->clots','$this->gravida','$this->parity','$this->abortion','$this->ftnd','$this->lscs','$this->fdhd')";
        // echo $sql;
        // exit;
        $res = mysqli_query($con, $sql);

        $date = date("Y-m-d H:i:s");
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
        $sql1->close();
        $con->close();


        return $res;
    }
    function updateHistory($pid)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();

        $sql = "UPDATE `patient_history` set `sign_symptoms1`='$this->text1',`sign_symptoms2`='$this->text2',`sign_symptoms3`='$this->text3',`sign_symptoms4`='$this->text4',`present_illness`='$this->vedana',`past_illness`='$this->poorva',`old_treatment`='$this->chikitsa',`family_history`='$this->kautumbika',`food`='$this->marital',`marital`='$this->vayaktika',`tobacco_chewing`='$this->tobacco',`smoking`='$this->smok',`alcohol`='$this->alco',`krodha`='$this->krodha',`shoka`='$this->shoka',`chittogvega`='$this->chittogvega',`atichintana`='$this->atichintana',`vegadharana`='$this->vegadharana',`udheerna`='$this->udheerna',`shift_duties`='$this->shift',`frequent_journey`='$this->frequent',`nidra`='$this->nidra',`agani`='$this->agani',`koshtas`='$this->koshtas',`menarche`='$this->menarche',`day_cycle`='$this->day',`labour`='$this->labour',`field_work`='$this->field_work',`sedantary`='$this->sedantary',`intellectual`='$this->intellectual',`job_stress`='$this->job_Stress',`period_regular`='$this->regular',`period_irregular`='$this->irregular',`period_painful`='$this->painful',`period_scanty`='$this->scanty',`period_withclot`='$this->clots',`gravida`='$this->gravida',`parity`='$this->parity',`abortion`='$this->abortion',`ftnd`='$this->ftnd',`lscs`='$this->lscs',`fthd`='$this->fdhd' WHERE `patient_id`='$pid'";
        // exit;
        $res = mysqli_query($con, $sql);

        $date = date("Y-m-d H:i:s");
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
        $sql1->close();
        $con->close();


        return $res;
    }

    function getHistory($pid)
    {

        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT `id`, `sign_symptoms1`, `sign_symptoms2`, `sign_symptoms3`, `sign_symptoms4`, `present_illness`, `past_illness`, `old_treatment`, `family_history`, `marital`, `food`, `tobacco_chewing`, `smoking`, `alcohol`, `krodha`, `shoka`, `chittogvega`, `atichintana`, `vegadharana`, `udheerna`, `shift_duties`, `frequent_journey`, `nidra`, `agani`, `koshtas`, `menarche`, `day_cycle`, `labour`, `field_work`, `sedantary`, `intellectual`, `job_stress`, `period_regular`, `period_irregular`, `period_painful`, `period_scanty`, `period_withclot`, `gravida`, `parity`, `abortion`, `ftnd`, `lscs`, `fthd` FROM `patient_history` WHERE `patient_id`='$pid'";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    function insertExamination($pid)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "INSERT INTO `patient_examintaion`(`patient_id`,`general_exam`,`pulse_rate`,`heart_rate`,`respiration_rate`,`temp`,`blood_pressure`,`nadi`,`mutra`,`mala`,`jivha_upalepita`,`jivha_ragayukta`,`jivha_others`,`jivha_paka`,`jivha_centrally_coated`,`shabda`,`sparsha`,`varna_ruksha`,`varna_snigdha`,`varna_tanu`,`varna_sthula`,`varna_ushna`,`drik_pale`,`drik_conjested`,`drik_kalushita`,`drik_akshimalasrava`,`drik_jalasrava`,`drik_raga`,`akriti`,`prakruti_type`,`prakruti_assess`,`saara_type`,`saara_assess`,`smhanana_type`,`smhanana_assess`,`pramana_type`,`pramana_assess`,`satmya_type`,`satmya_assess`,`satwa_type`,`satwa_assess`,`ahara_shakti_type`,`ahara_shakti_assess`,`vyayama_shakti_type`,`vyayama_shakti_assess`,`vayah_type`,`vayah_assess`,`manah`,`buddhi`,`sanjna`,`jnana`,`bhakti`,`sheela`,`chesta`,`achara`,`smriti`,`storus1`,`storus2`,`storus3`,`dustilakshna1`,`dustilakshna2`,`dustilakshna3`,`cvc`,`local_exam`,`roga_type`,`rs`,`pa`,`cns`,`power`,`reflexes`,`tone`,`others`,`gus`,`mss`,`skin`) 
    VALUES ('$pid','$this->general','$this->pulse','$this->heart','$this->resp','$this->temp','$this->bp','$this->nadi','$this->mutra','$this->mala','$this->upalepita','$this->ragayukta','$this->jivaothers','$this->paka','$this->cc','$this->shabda','$this->sparsha','$this->ruksha','$this->snigdha','$this->tanu','$this->sthula','$this->ushna','$this->pale','$this->conjested','$this->kalushita','$this->akshimalasrava','$this->jalasrava','$this->raga','$this->akriti','$this->praktuti_type','$this->praktuti_assement','$this->saara_type','$this->saara_assement','$this->smhanana_type','$this->smhanana_assement','$this->pramana_type','$this->pramana_assement','$this->satmya_type','$this->satmya_assement','$this->satwa_type','$this->satwa_assement','$this->aharashakti_type','$this->aharashakti_assement','$this->vyayama_shakti_type','$this->vyayama_shakti_assement','$this->vayah_type','$this->vayah_assement','$this->manah','$this->buddhi','$this->sanjna','$this->jnana','$this->bhakti','$this->sheela','$this->chesta','$this->achara','$this->smriti','$this->storus1','$this->type1','$this->storus2','$this->type2','$this->storus3','$this->type3','$this->csv','$this->local_exam','$this->rogatype','$this->rs','$this->pa','$this->cns','$this->power','$this->reflexes','$this->tone','$this->others','$this->gus','$this->mss','$this->skin')";

        $sql1 = "INSERT INTO `patient_examintaion` (`patient_id`, `date`, `pulse`, `respiration`, `temp`, `bp`, `wh`, `built`, `scalp`, `eyes`, `nose`, `oral_cavity`, `tounge`, `neck`, `nails`, `edema`, `lymph`, `anemia`, `cyanosis`, `jaundice`, `pigmentation`, `others`, `rr`, `chest_shape`, `r_nose`, `r_throat`, `r_cyanosis`, `others_a`, `resp_moment`, `mediastinum`, `tenderness`, `others_b`, `resonant`, `cardiac_dullness`, `liver_dullness`, `other_dullness`, `hyper`,`a_breath_sounds`,`broncho`,`breath_sounds`,`added_sounds`, `pr`, `rythom`, `volume`, `character`, `bp2`, `ccf`, `tender`, `pedal`, `other_clubing`, `c_cyanosis`, `c_anemia`, `chest_wall`, `apex_beat`, `pulsation`, `beat_loaction`, `i_character`, `tender_areas`, `other_location`, `i_character2`, `tender_areas2`, `thrills`, `diastolic`, `dull_loaction`, `dull_character`, `sound1`, `sound2`, `splitting`, `a_added_sounds`, `site`, `heart_timing`, `heart_char`, `pitch`, `grade`, `present`, `absent`, `other_heart`, `lips`, `teeth`, `g_toungue`, `tonsils`, `shape`, `g_respiration`, `umbilicus`, `hernial`, `vessels`, `striae`, `p_tenderness`, `rigidity`, `liver_size`, `liver_tender`, `liver_margin`, `liver_cons`, `spleen_size`, `spleen_tender`, `spleen_margin`, `spleen_cons`, `swelling`, `rebound`, `precussion`, `dullness_grade1`, `dullness_grade2`, `dullness_grade3`, `dullness_grade`, `peristalitic`, `rubs`, `rectal`, `mental_status`, `memory`, `orientation`, `speech`, `behavior`, `gait`, `nerve_exam`, `nutrition`, `muscle_score`, `muscle_tone`, `coordinations`, `involuntary`, `touch`, `pain`, `sup_temp`, `crude_touch`, `vibration`, `joint_sense`, `tactile_l`, `tactile_d`, `tactile_e`, `tactile_o`, `signs1`, `signs2`, `signs3`, `jerks1`, `jerks2`, `jerks3`, `jerks4`, `abdominal`, `plantar`, `jaw_jerk`, `knee_reflex`, `ankle_reflex`, `any_other`) VALUES ( '$pid', CURRENT_TIMESTAMP, '$this->pulse', '$this->respiration', '$this->temp', '$this->bp', '$this->wh', '$this->built', '$this->scalp', '$this->eyes', '$this->nose', '$this->oral_cavity', '$this->tounge', '$this->neck', '$this->nails', '$this->edema', '$this->lymph', '$this->anemia', '$this->cyanosis', '$this->jaundice', '$this->pigmentation', '$this->others','$this->rr','$this->chest_shape','$this->r_nose','$this->r_throat','$this->r_cyanosis','$this->others_a','$this->resp_moment','$this->mediastinum','$this->tenderness','$this->others_b','$this->resonant','$this->cardiac_dullness','$this->liver_dullness','$this->other_dullness','$this->hyper','$this->a_breath_sounds','$this->broncho','$this->breath_sounds','$this->added_sounds','$this->pr','$this->rythom','$this->volume','$this->character','$this->bp2','$this->ccf','$this->tender','$this->pedal','$this->other_clubing','$this->c_cyanosis','$this->c_anemia','$this->chest_wall','$this->apex_beat','$this->pulsation','$this->beat_loaction','$this->charecter','$this->tender_areas','$this->other_location','$this->charecter2','$this->tender_areas2','$this->thrills','$this->diastolic','$this->dull_loaction','$this->dull_charecter','$this->sound1','$this->sound2','$this->splitting','$this->a_added_sounds','$this->site','$this->heart_timing','$this->heart_char','$this->pitch','$this->grade','$this->present','$this->absent','$this->other_heart','$this->lips','$this->teeth','$this->g_toungue','$this->tonsils','$this->shape','$this->g_respiration','$this->umbilicus','$this->hernial','$this->vessels','$this->striae','$this->p_tenderness','$this->rigidity','$this->liver_size','$this->liver_tender','$this->liver_margin','$this->liver_cons','$this->spleen_size','$this->spleen_tender','$this->spleen_margin','$this->spleen_cons','$this->swelling','$this->rebound','$this->precussion','$this->dullness_grade1','$this->dullness_grade2','$this->dullness_grade3','$this->dullness_grade','$this->peristalitic','$this->rubs','$this->rectal','$this->mental_status','$this->memory','$this->orientation','$this->speech','$this->behavior','$this->gait','$this->nerve_exam','$this->nutrition','$this->muscle_score','$this->muscle_tone','$this->coordinations','$this->involuntary','$this->touch','$this->pain','$this->sup_temp','$this->crude_touch','$this->vaibration','$this->joint_sense','$this->tactile_l','$this->tactile_d','$this->tactile_e','$this->tactile_o','$this->signs1','$this->signs2','$this->signs3','$this->jerks1','$this->jerks2','$this->jerks3','$this->jerks4','$this->abdominal','$this->plantar','$this->jaw_jerk','$this->knee_reflex','$this->ankle_reflex','$this->any_other')";


        $res = mysqli_query($con, $sql1);

        $date = date("Y-m-d H:i:s");
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
        $sql1->close();
        $con->close();


        return $res;
    }

    function getExamination($pid)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT * FROM `patient_examintaion` WHERE `patient_id`='$pid'";
        // echo $sql;
        $res = mysqli_query($con, $sql);
        return $res;
    }
    function updateExamination($pid)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();

        $sql = "UPDATE `patient_examintaion` set `pulse`='$this->pulse', `respiration`='$this->respiration', `temp`='$this->temp', `bp`='$this->bp', `wh`='$this->wh', `built`='$this->built', `scalp`='$this->scalp', `eyes`='$this->eyes', `nose`='$this->nose', `oral_cavity`='$this->oral_cavity', `tounge`='$this->tounge', `neck`='$this->neck', `nails`='$this->nails', `edema`='$this->edema', `lymph`='$this->lymph', `anemia`='$this->anemia', `cyanosis`='$this->cyanosis', `jaundice`='$this->jaundice', `pigmentation`='$this->pigmentation', `others`='$this->others', `rr`='$this->rr', `chest_shape`='$this->chest_shape', `r_nose`='$this->r_nose', `r_throat`='$this->r_throat', `r_cyanosis`='$this->r_cyanosis', `others_a`='$this->others_a', `resp_moment`='$this->resp_moment', `mediastinum`='$this->mediastinum', `tenderness`='$this->tenderness', `others_b`='$this->others_b', `resonant`='$this->resonant', `cardiac_dullness`='$this->cardiac_dullness', `liver_dullness`='$this->liver_dullness', `other_dullness`='$this->other_dullness', `hyper`='$this->hyper',`a_breath_sounds`='$this->a_breath_sounds',`broncho`='$this->broncho',`breath_sounds`='$this->breath_sounds',`added_sounds`='$this->added_sounds', `pr`='$this->pr', `rythom`='$this->rythom', `volume`='$this->volume', `character`='$this->character', `bp2`='$this->bp2', `ccf`='$this->ccf', `tender`='$this->tender', `pedal`='$this->pedal', `other_clubing`='$this->other_clubing', `c_cyanosis`='$this->c_cyanosis', `c_anemia`='$this->c_anemia', `chest_wall`='$this->chest_wall', `apex_beat`='$this->apex_beat', `pulsation`='$this->pulsation', `beat_loaction`='$this->beat_loaction', `i_character`='$this->charecter', `tender_areas`='$this->tender_areas', `other_location`='$this->other_location', `i_character2`='$this->charecter2', `tender_areas2`='$this->tender_areas2', `thrills`='$this->thrills', `diastolic`='$this->diastolic', `dull_loaction`='$this->dull_loaction', `dull_character`='$this->dull_charecter', `sound1`='$this->sound1', `sound2`='$this->sound2', `splitting`='$this->splitting', `a_added_sounds`='$this->a_added_sounds', `site`='$this->site', `heart_timing`='$this->heart_timing', `heart_char`='$this->heart_char', `pitch`='$this->pitch', `grade`='$this->grade', `present`='$this->present', `absent`='$this->absent', `other_heart`='$this->other_heart', `lips`='$this->lips', `teeth`='$this->teeth', `g_toungue`='$this->g_toungue', `tonsils`='$this->tonsils', `shape`='$this->shape', `g_respiration`='$this->g_respiration', `umbilicus`='$this->umbilicus', `hernial`='$this->hernial', `vessels`='$this->vessels', `striae`='$this->striae', `p_tenderness`='$this->p_tenderness', `rigidity`='$this->rigidity', `liver_size`='$this->liver_size', `liver_tender`='$this->liver_tender', `liver_margin`='$this->liver_margin', `liver_cons`='$this->liver_cons', `spleen_size`='$this->spleen_size', `spleen_tender`='$this->spleen_tender', `spleen_margin`='$this->spleen_margin', `spleen_cons`='$this->spleen_cons', `swelling`='$this->swelling', `rebound`='$this->rebound', `precussion`='$this->precussion', `dullness_grade1`='$this->dullness_grade1', `dullness_grade2`='$this->dullness_grade2', `dullness_grade3`='$this->dullness_grade3', `dullness_grade`='$this->dullness_grade', `peristalitic`='$this->peristalitic', `rubs`='$this->rubs', `rectal`='$this->rectal', `mental_status`='$this->mental_status', `memory`='$this->memory', `orientation`='$this->orientation', `speech`='$this->speech', `behavior`='$this->behavior', `gait`='$this->gait', `nerve_exam`='$this->nerve_exam', `nutrition`='$this->nutrition', `muscle_score`='$this->muscle_score', `muscle_tone`='$this->muscle_tone', `coordinations`='$this->coordinations', `involuntary`='$this->involuntary', `touch`='$this->touch', `pain`='$this->pain', `sup_temp`='$this->sup_temp', `crude_touch`='$this->crude_touch', `vibration`='$this->vaibration', `joint_sense`='$this->joint_sense', `tactile_l`='$this->tactile_l', `tactile_d`='$this->tactile_d', `tactile_e`='$this->tactile_e', `tactile_o`='$this->tactile_o', `signs1`='$this->signs1', `signs2`='$this->signs2', `signs3`='$this->signs3', `jerks1`='$this->jerks1', `jerks2`='$this->jerks2', `jerks3`='$this->jerks3', `jerks4`='$this->jerks4', `abdominal`='$this->abdominal', `plantar`='$this->plantar', `jaw_jerk`='$this->jaw_jerk', `knee_reflex`='$this->knee_reflex', `ankle_reflex`='$this->ankle_reflex', `any_other`='$this->any_other' WHERE `patient_id`='$pid'";

        $res = mysqli_query($con, $sql);

        $date = date("Y-m-d H:i:s");
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
        $sql1->close();
        $con->close();

        return $res;
    }
    function getLab()
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        if ($_SESSION['role'] == 'Radiology') {
            $labid = " and is_radio=1";
        } else {
            $labid = "";
        }
        $sql = "SELECT ln.`testid`,lg.`testname`,ln.`test_name` FROM `lab_test` lg,`labtest_group` ln WHERE lg.`testid`=ln.`labid` and `status`='active' $labid order by lg.`testid`,ln.`testid`";
        //   echo $sql;exit;
        $res = mysqli_query($con, $sql);
        return $res;
    }
    function insertIPLab($lab, $pid, $date)
    {
        // $date=date("Y-m-d");
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $dt = date("Y-m-d", strtotime($date));


        $sql = "select * from `central_ipd` where ipd_no='$pid'";
        // echo $sql;exit;
        $ip_res = mysqli_query($con, $sql);

        $month = date('n'); // Numeric representation of month (1-12)
        $year = date('Y');

        if ($month >= 4) {
            // April to December
            $startYear = $year;
            $endYear = $year + 1;
        } else {
            // January to March
            $startYear = $year - 1;
            $endYear = $year;
        }

        $financialYear = "$startYear-" . substr($endYear, -2);


        if (mysqli_num_rows($ip_res) > 0) {
            $bill_row = mysqli_fetch_assoc($ip_res);
            if ($bill_row['is_generated'] == 1) {
                $query = "SELECT * FROM data_table WHERE id=7";
                $bill = mysqli_fetch_assoc(mysqli_query($con, $query));
                $bill_no = $bill['table_id'];

                $bill_no = $bill_no + 1;
                $fi_bill_no = 'LAB/' . $financialYear . '/' . sprintf("%04d", $bill_no);

                $sql = "INSERT INTO `billing` ( `patient_id`, `amount`, `service_level_discount`, `discount_amount`, `paid_amount`, `outstand_amount`, `bill_no`, `doctor`, `dept_id`,  `service_type`, `patient_type`, `status`, `collected_by`, `collected_at`,`is_generated`,`discount_reason`) VALUES 
                               ( '$bill_row[ipd_no]', '0', '0', '0', '0', '0', '$fi_bill_no', '$bill_row[doctor]', '$bill_row[dept_id]', 'Pathology', 'IP', 'PAID', '$_SESSION[name]', NOW(),'1','CAMP')";
                //   echo $sql;exit;
                $res = mysqli_query($con, $sql);
                $id = mysqli_insert_id($con);
                if ($id > 0) {
                    $query1 = "UPDATE data_table set table_id='$bill_no' WHERE id=7";
                    mysqli_query($con, $query1);
                }
            }
        }

        $date = date('Ymd', strtotime($dt)); // 20250625
        $prefix = 'LAB-' . $date . '-';
        $query1 = "SELECT visit_no,patient_id FROM lab_report WHERE visit_no LIKE '$prefix%' ORDER BY visit_no DESC LIMIT 1";
        $res1 = mysqli_query($con, $query1);

        if ($row1 = mysqli_fetch_assoc($res1)) {

            $last_no = (int)substr($row1['visit_no'], -4); // Extract last 4 digits
            $new_no = str_pad($last_no + 1, 4, '0', STR_PAD_LEFT);
            $visit_no = $prefix . $new_no;
        } else {
            $new_no = '0001';
            $visit_no = $prefix . $new_no;
        }

        // 
        // echo $query1.$visit_no;exit;
        // Tests already recorded for this bill on this save. Replaces the old
        // $test_id_old check, which only compared against the previous iteration
        // and so let an interleaved parameter order create duplicates.
        $sample_seen = array();
        foreach ($lab as $key => $value) {

            $query2 = "SELECT * FROM `lab_test_parameter` where test_id=$value and status='active'";
            $res2 = mysqli_query($con, $query2);
            while ($row2 = mysqli_fetch_assoc($res2)) {
                $parameter_id = $row2['id'];
                $test_id = $row2['test_id'];
                // Guard on both this save and what is already on file: another path
                // (sample_collection.php, or an earlier save of this treatment) may have
                // recorded the same bill/test already.
                $dup_chk = mysqli_query($con, "SELECT id FROM sample_status
                                        WHERE bill_no='" . mysqli_real_escape_string($con, (string) $fi_bill_no) . "'
                                          AND patient_id='" . mysqli_real_escape_string($con, (string) $pid) . "'
                                          AND test_id='" . mysqli_real_escape_string($con, (string) $test_id) . "'
                                        LIMIT 1");
                $already = ($dup_chk && mysqli_num_rows($dup_chk) > 0);
                if (!isset($sample_seen[$test_id]) && !$already) {
                    $dt_sp = date("Y-m-d H:i:s", strtotime($dt . " +10 minutes"));
                    $dt_ac = date("Y-m-d H:i:s", strtotime($dt_sp . " +10 minutes"));
                    $dt_fz = date("Y-m-d H:i:s", strtotime($dt_sp . " +90 minutes"));
                    $sql = "INSERT IGNORE INTO sample_status(`bill_no`, `patient_id`, `test_id`, `is_sample_collected`, `sample_collected_by`, `sample_collected_at`, `is_sample_acknowledge`, `sample_acknowledged_by`, `sample_acknowledged_at`)
             VALUES ('$fi_bill_no','$pid','$test_id','1','lab','$dt_sp','1','lab','$dt_ac')";

                    mysqli_query($con, $sql);
                    if ($id > 0) {
                        $sql1 = "INSERT INTO `billing_details` (`bill_id`, `bill_no`, `service_id`, `doctor_id`, `unit`, `price`, `discount`, `status`, `created_at`) VALUES 
                                    ( '$id', '$fi_bill_no', '$test_id', '0', '1', '0', '0', 'PAID', NOW())";
                        $res = mysqli_query($con, $sql1);
                    }
                }
                $query = "INSERT INTO lab_report (parameter_id,date,patient_id,patient_type,visit_no,testid,bill_no) 
                                          VALUES ('$parameter_id', '$dt', '$pid', 'IP', '$visit_no', '$test_id','$fi_bill_no')";
                mysqli_query($con, $query);

                // echo $query;exit;
                $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
                $sql1->bind_param("sss", $query, $_SESSION['name'], $date);
                $sql1->execute();
                $sql1->close();
                $sample_seen[$test_id] = true;
            }
        }
        // exit;
        $con->close();
        return $fi_bill_no;
    }
    function getPatientLab($id)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        if ($_SESSION['role'] == 'Radiology') {
            $labid = "8,9,10,11";
        } else {
            $labid = "1,2,3,4,5,6,7";
        }
        $sql = "SELECT ln.`testid`,lg.`testname`,ln.`test_name`,lr.`test_result`,lr.id,lr.date FROM `lab_test` lg,`labtest_group` ln,`lab_report` lr WHERE lg.`testid`=ln.`labid` and ln.labid in ($labid) and `status`='active' and lr.`patient_id`='$id' and patient_type='IP' and lr.`lab_result`=ln.`testid`  order by lg.`testid`";
        //   echo $sql;exit;
        $res = mysqli_query($con, $sql);
        return $res;
    }


    function getPatientLab1($id)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();

        $sql = "SELECT lt.`testname`,lr.`id`,lr.`date` as ldate,lr.visit_no FROM `lab_report` lr,lab_test lt WHERE lt.`testid` =lr.`testid` and lr.`patient_id`='$id' GROUP BY lr.visit_no order by lr.date desc";
        //   echo $sql;exit;
        $res = mysqli_query($con, $sql);
        return $res;
    }



    function getLabTests()
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT lt.* FROM lab_test lt where  lt.status='active' order by testname";
        $res = mysqli_query($con, $sql);
        return $res;
    }


    function insertInvest()
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "INSERT INTO `patient_investigation`(`patient_id`, `nindana`, `poorvapoorva`, `roopa`, `anupashaya`, `dosha`, `dushy`, `agni`, `ama`, `srotas`, `srotodushtiprakarana`, `srotodustilakshana`, `vyadhiudbhavasthana`, `sancharasthana`, `vyaktasthana`, `vyadhiadhisthana`, `rogamarga`, `rogabala`, `rogibala`, `rogaswabhava`, `upadrava`, `arista_lakshna`, `samprapti`, `sapeksha`, `nidana`, `vinischaya`) 
        VALUES ('$this->pid','$this->nindana','$this->poorvapoorva','$this->roopa','$this->upashaya','$this->dosha','$this->dushy','$this->agni','$this->ama','$this->srotas','$this->sroto','$this->lakshana','$this->vyadhi','$this->sanchara','$this->vyaktasthana','$this->vyadhiadhisthana','$this->rogamarga','$this->rogabala','$this->rogibala','$this->rogaswabhava','$this->upadrava','$this->arista','$this->samprapti','$this->sapeksha','$this->nidana','$this->vyadi')";
        // echo $sql; exit;
        $res = mysqli_query($con, $sql);
        return $res;
    }
    function getInvest($id)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT * FROM `patient_investigation` WHERE `patient_id`='$id'";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    function updateInvest()
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "UPDATE `patient_investigation` SET `nindana`='$this->nindana',`poorvapoorva`='$this->poorvapoorva',`roopa`='$this->roopa',`anupashaya`='$this->upashaya',`dosha`='$this->dosha',`dushy`='$this->dushy',`agni`='$this->agni',`ama`='$this->ama',`srotas`='$this->srotas',`srotodushtiprakarana`='$this->sroto',`srotodustilakshana`='$this->lakshana',`vyadhiudbhavasthana`='$this->vyadhi',`sancharasthana`='$this->sanchara',`vyaktasthana`='$this->vyaktasthana',`vyadhiadhisthana`='$this->vyadhiadhisthana',`rogamarga`='$this->rogamarga',`rogabala`='$this->rogabala',`rogibala`='$this->rogibala',`rogaswabhava`='$this->rogaswabhava',`upadrava`='$this->upadrava',`arista_lakshna`='$this->arista',`samprapti`='$this->samprapti',`sapeksha`='$this->sapeksha',`nidana`='$this->nidana',`vinischaya`='$this->vyadi' WHERE `patient_id`='$this->pid' ";
        // echo $sql; exit;
        $res = mysqli_query($con, $sql);

        $date = date("Y-m-d H:i:s");
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
        $sql1->close();
        $con->close();


        return $res;
    }
    function getMedic()
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT * from `medicine` where `status`='active'";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    function getPatienMedic($id)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT im.*,m.`product_name`,m.rx_id from `ip_medicine` im,`rx_pharmacy` m where m.id=im.`medicine` and `patient_id`='$id'";
        $res = mysqli_query($con, $sql);
        return $res;
    }

    function ensureRoundAssessmentTable($con)
    {
        $sql = "CREATE TABLE IF NOT EXISTS `ip_round_assessment` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `patient_id` varchar(50) NOT NULL,
            `round_datetime` datetime NOT NULL,
            `service_id` int(11) NOT NULL,
            `doctor_id` int(11) NOT NULL DEFAULT '0',
            `qty` decimal(10,2) NOT NULL DEFAULT '0.00',
            `status` varchar(20) NOT NULL DEFAULT 'active',
            `created_by` varchar(100) DEFAULT NULL,
            `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_patient_id` (`patient_id`),
            KEY `idx_round_datetime` (`round_datetime`),
            KEY `idx_service_id` (`service_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        mysqli_query($con, $sql);
        // Add doctor_id column if this is an existing table without it
        $chk = mysqli_query($con, "SHOW COLUMNS FROM `ip_round_assessment` LIKE 'doctor_id'");
        if (mysqli_num_rows($chk) == 0) {
            mysqli_query($con, "ALTER TABLE `ip_round_assessment` ADD COLUMN `doctor_id` int(11) NOT NULL DEFAULT '0' AFTER `service_id`");
        }
    }

    function getRoundServiceList()
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT `id`,`name`,`is_doctor` FROM `service_list` WHERE `status`='active' and service_group_id in (select id FROM `service_group` WHERE bill_group in ('ROOM CHARGES','OTHER CHARGES','ADMISSION CHARGES','OPRATION CHARGES','PHYSIOTHERAPY CHARGES' ,'PATHOLOGY TEST')) ORDER BY `name`";
        $res = mysqli_query($con, $sql);
        return $res;
    }

    function insertRoundAssessmentBatch($id, $rounds)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $this->ensureRoundAssessmentTable($con);

        $patient_id = mysqli_real_escape_string($con, $id);
        $inserted = 0;

        if (is_array($rounds)) {
            foreach ($rounds as $round) {
                $service_id = isset($round['service_id']) ? (int)$round['service_id'] : 0;
                $qty = isset($round['qty']) ? (float)$round['qty'] : 0;
                $round_datetime_raw = isset($round['round_datetime']) ? $round['round_datetime'] : '';
                $timestamp = strtotime($round_datetime_raw);

                if ($service_id <= 0 || $qty <= 0 || $timestamp === false) {
                    continue;
                }

                $round_datetime = mysqli_real_escape_string($con, date("Y-m-d H:i:s", $timestamp));
                $qty = number_format($qty, 2, '.', '');

                $doctor_id = isset($round['doctor_id']) ? (int)$round['doctor_id'] : 0;
                $sql = "INSERT INTO `ip_round_assessment`(`patient_id`,`round_datetime`,`service_id`,`doctor_id`,`qty`,`status`,`created_by`,`created_at`) 
                VALUES ('$patient_id','$round_datetime','$service_id','$doctor_id','$qty','active','$_SESSION[name]',NOW())";
                $res = mysqli_query($con, $sql);

                if ($res) {
                    $inserted++;
                }

                $date = date("Y-m-d H:i:s");
                $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
                $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
                $sql1->execute();
                $sql1->close();
            }
        }

        $con->close();
        return $inserted;
    }

    function getRoundAssessments($id)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $this->ensureRoundAssessmentTable($con);

        $patient_id = mysqli_real_escape_string($con, $id);
        $sql = "SELECT ra.*, sl.`name` as service_name, CONCAT(d.`name`,' ',d.`last_name`) as doctor_name
              FROM `ip_round_assessment` ra
              LEFT JOIN `service_list` sl ON sl.`id`=ra.`service_id`
              LEFT JOIN `doctor` d ON d.`id`=ra.`doctor_id` AND ra.`doctor_id`>0
              WHERE ra.`patient_id`='$patient_id' AND ra.`status`='active'
              ORDER BY ra.`round_datetime` DESC, ra.`id` DESC";
        $res = mysqli_query($con, $sql);
        return $res;
    }

    function insertIPMedic($id)
    {
        $date = date("Y-m-d");
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();

        $rxId = trim((string)$this->omedicine);
        $productName = trim((string)$this->omedicine_name);

        if ($productName == '') {
            $productName = 'Unknown Medicine';
        }

        if ($rxId != '') {
            $query = "SELECT * FROM `rx_pharmacy` where rx_id='" . mysqli_real_escape_string($con, $rxId) . "'";
        } else {
            $query = "SELECT * FROM `rx_pharmacy` where (rx_id='' OR rx_id IS NULL) AND product_name='" . mysqli_real_escape_string($con, $productName) . "'";
        }
        // echo $query;exit;
        $res = mysqli_query($con, $query);
        $row = mysqli_fetch_assoc($res);
        if (isset($row['id']) && $row['id'] > 0) {
            $medicine_id = $row['id'];
        } else {
            $safeRxId = mysqli_real_escape_string($con, $rxId);
            $safeProductName = mysqli_real_escape_string($con, $productName);
            if ($rxId != '') {
                $query1 = "INSERT INTO `rx_pharmacy`(`rx_id`, `product_name`) VALUES ('$safeRxId','$safeProductName')";
            } else {
                $query1 = "INSERT INTO `rx_pharmacy`(`rx_id`, `product_name`) VALUES (NULL,'$safeProductName')";
            }
            // echo $query1;exit;
            mysqli_query($con, $query1);
            $medicine_id = mysqli_insert_id($con);
        }

        $sql = "INSERT INTO `ip_medicine`(`patient_id`, `date`, `medicine`, `qty`,`created_by`,`created_at`) VALUES ('$id','$date','$medicine_id','$this->qty','$_SESSION[name]',NOW())";
        // echo $sql;exit;
        $res = mysqli_query($con, $sql);

        $ipid = mysqli_insert_id($con);

        $date = date("Y-m-d H:i:s");
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
        $sql1->close();
        $con->close();

        //    exit;    
        return $ipid;
    }
    function updateIPMedic($id, $rx_id, $dcno)
    {
        $date = date("Y-m-d");
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "UPDATE `ip_medicine` set rx_so_id='$rx_id', dc_no='$dcno' where id in ($id)";
        // echo $sql;exit;
        $res = mysqli_query($con, $sql);
    }
    function ensureIpdRxId($ipdNo)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();

        $ipdNo = trim((string)$ipdNo);
        if ($ipdNo == '') {
            return null;
        }

        $ipdNoSafe = mysqli_real_escape_string($con, $ipdNo);
        $sql = "SELECT ci.id, ci.name, ci.mobile, ci.address, ci.gender, ci.rx_id, ci.uhid as ipd_uhid, co.uhid as opd_uhid
                FROM central_ipd ci
                LEFT JOIN central_opd co ON co.opd_no = ci.opd_no
                WHERE ci.ipd_no='$ipdNoSafe'
                ORDER BY ci.id DESC
                LIMIT 1";
        $res = mysqli_query($con, $sql);
        if (!$res) {
            return null;
        }

        $row = mysqli_fetch_assoc($res);
        if (!isset($row['id']) || (int)$row['id'] <= 0) {
            return null;
        }

        if (isset($row['rx_id']) && trim((string)$row['rx_id']) != '') {
            return trim((string)$row['rx_id']);
        }

        $name = trim((string)$row['name']);
        $address = trim((string)$row['address']);
        $phoneClean = preg_replace('/\D+/', '', (string)$row['mobile']);
        $customerId = null;

        $gender = strtoupper(trim((string)$row['gender']));
        if (in_array($gender, array('M', 'MALE'))) {
            $gender = 'MALE';
        } elseif (in_array($gender, array('F', 'FEMALE'))) {
            $gender = 'FEMALE';
        }

        if ($phoneClean != '') {
            $searchCurl = curl_init();
            curl_setopt_array($searchCurl, array(
                CURLOPT_URL => 'https://api.retailxpress.ai/v1/external/customer?page=1&limit=20&sort_by=createdAt&sort_order=desc&search=' . urlencode($phoneClean),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'x-api-key: 8417daa38eb33753f60ecbabe28d359bea4b4589d8c791f563b00b3de7c53543'
                ),
            ));
            $searchResponse = curl_exec($searchCurl);
            curl_close($searchCurl);
            $searchResult = json_decode($searchResponse, true);

            $candidates = array();
            if (isset($searchResult['data']['items']) && is_array($searchResult['data']['items'])) {
                $candidates = $searchResult['data']['items'];
            } elseif (isset($searchResult['data']) && is_array($searchResult['data'])) {
                $candidates = $searchResult['data'];
            }

            foreach ($candidates as $candidate) {
                if (isset($candidate['full_name']) && strcasecmp(trim((string)$candidate['full_name']), $name) === 0) {
                    $customerId = $candidate['id'];
                    break;
                }
            }
        }

        if (empty($customerId)) {
            $uniqueCode = trim((string)$row['ipd_uhid']);
            if ($uniqueCode == '') {
                $uniqueCode = trim((string)$row['opd_uhid']);
            }
            if ($uniqueCode == '') {
                $uniqueCode = 'IPD' . $ipdNo;
            }

            $customerPayload = array(
                'full_name' => $name,
                'phone_number' => $phoneClean,
                'address' => $address,
                'gender' => $gender,
                'unique_code' => $uniqueCode
            );

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.retailxpress.ai/v1/external/customer',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($customerPayload),
                CURLOPT_HTTPHEADER => array(
                    'x-api-key: 8417daa38eb33753f60ecbabe28d359bea4b4589d8c791f563b00b3de7c53543',
                    'Content-Type: application/json'
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $createResult = json_decode($response, true);
            $customerId = $createResult['data']['id'] ?? null;
        }

        if (!empty($customerId)) {
            $safeId = mysqli_real_escape_string($con, (string)$customerId);
            mysqli_query($con, "UPDATE `central_ipd` SET `rx_id`='$safeId' WHERE `ipd_no`='$ipdNoSafe'");
            return $customerId;
        }

        return null;
    }
    function insertDaily()
    {
        $date = date("Y-m-d H:i:s");
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "INSERT INTO `daily_entry`(`patient_id`, `pulse`, `temp`, `bp`, `spo2`, `diet`, `medicine`, `progress`, `date`, `rbs`, `urine_output`, `respiratory_rate`,`created_by`) 
        VALUES ('$this->pid','$this->pulse','$this->temp','$this->bp','$this->spo2','$this->diet','$this->medicin','$this->progress','$date','$this->rbs','$this->urine_output','$this->respiratory_rate','$_SESSION[name]')";
        // echo $sql;exit;
        $res = mysqli_query($con, $sql);

        $date = date("Y-m-d H:i:s");
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
        $sql1->close();
        $con->close();


        return $res;
    }
    function getDaily($id)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT * FROM `daily_entry` WHERE `patient_id`='$id'";
        // echo $sql;
        $res = mysqli_query($con, $sql);
        return $res;
    }
    function insertProceMedic($id)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "INSERT INTO `therapies`( `patient_id`, `date`, `procedure`, `sub_procedure`, `medicine`, `days`, `qty`, `total`, `morning`, `afternoon`, `evening`, `night`, `observation`) 
        VALUES ('$id','$this->pdate','$this->procedure','$this->subprocedure','$this->omedicine','$this->roga','$this->qty','$this->total1','$this->morning','$this->afternoon','$this->evening','$this->night','$this->anupana')";
        // echo $sql;exit;
        $res = mysqli_query($con, $sql);

        $date = date("Y-m-d H:i:s");
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
        $sql1->close();
        $con->close();


        return $res;
    }
    function getProceMedic($id)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT ot.*,p.procedure_name,sp.subprocedure_name,m.medicine as proce_medic from `procedure` p,`procedure_medicine` m,`therapies` ot left join sub_procedure sp on ot.sub_procedure=sp.id WHERE `patient_id`='$id' and p.id=ot.procedure and m.id=ot.medicine";
        // echo $sql;
        $res = mysqli_query($con, $sql);
        return $res;
    }

    function getIpPatient($id)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT * from `central_ipd` WHERE ipd_no='$id'";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    function getOT_type()
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT * FROM `ot_type` WHERE `status`='active'";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    function getdoctor()
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT * FROM `doctor` WHERE  `status`='active'";
        $res = mysqli_query($con, $sql);
        return $res;
    }
    function getOT_details($id)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT * FROM `ot_procedures` WHERE `ot_id`='$id' and `status`='active'";
        // echo $sql;exit;
        $res = mysqli_query($con, $sql);
        return $res;
    }
    function insertOTReg()
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "INSERT INTO `operation_theater`( `patient_id`, `date`, `doctor`, `ot_type`, `comment`, `ot_procedure`, `created_by`) VALUES ('$this->ip_no','$this->otdate','$this->doctor','$this->ottype','$this->otcomments','$this->otprocedure','$_SESSION[name]')";
        // echo $sql;exit;
        $res = mysqli_query($con, $sql);

        $date = date("Y-m-d H:i:s");
        $sql1 = $con->prepare("INSERT INTO `query`(`sql_query`, `created_by`, `created_at`) VALUES (?, ?, ?)");
        $sql1->bind_param("sss", $sql, $_SESSION['name'], $date);
        $sql1->execute();
        $sql1->close();
        $con->close();


        return $res;
    }

    function getOTReg($id)
    {
        $newlogin = new userlogin;
        $con = $newlogin->dbconnect();
        $sql = "SELECT ot.id,ot.patient_id,ot.date,ot.comment,ot.doctor,o.ot_name,op.ot_procedure FROM ot_type o,`operation_theater` ot left join ot_procedures op on  op.id=ot.ot_procedure WHERE ot.`patient_id`='$id'  and o.id=ot.ot_type";
        // echo $sql;exit;
        $res = mysqli_query($con, $sql);
        return $res;
    }
}
