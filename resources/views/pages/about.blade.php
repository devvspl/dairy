@extends('layouts.public')
@section('title', 'About Us')
@section('meta_description', 'Learn more about our company')
@section('content')
    <style>
        #abpgAboutPage {
            --bg: #ffffff;
            --soft: #f6f8f2;
            --soft2: #fbfcf8;
            --text: #1f2a1a;
            --muted: #5c6b55;
            --brand: #293879;
            --accent: #f1cc24;
            --border: rgba(0, 0, 0, .10);
            --shadow: 0 18px 60px rgba(0, 0, 0, .08);
            --shadow2: 0 26px 80px rgba(0, 0, 0, .12);
            --radius: 22px;
            color: var(--text);
            background: var(--bg);
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial;
        }

        #abpgAboutPage * {
            box-sizing: border-box;
        }

        #abpgAboutPage img {
            max-width: 100%;
            display: block;
        }

        #abpgAboutPage .container {
            max-width: 1250px;
            margin: 0 auto;
            padding: 0 24px;
        }

        #abpgAboutPage .abpg-sec-head {
            text-align: center;
            max-width: 820px;
            margin: 0 auto 28px;
        }

        #abpgAboutPage .abpg-sec-head-left {
            text-align: left;
            margin-left: 0;
        }

        #abpgAboutPage .abpg-kicker {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border-radius: 999px;
            font-weight: 900;
            letter-spacing: .9px;
            text-transform: uppercase;
            font-size: 12px;
            color: #263d18;
            background: rgba(38, 61, 24, .07);
            border: 1px solid rgba(38, 61, 24, .12);
        }

        #abpgAboutPage .abpg-sec-head h2 {
            margin: 12px 0 8px;
            font-size: clamp(26px, 3.2vw, 42px);
            font-weight: 950;
            letter-spacing: -.4px;
            color: #00000;
            line-height: 1.08;
        }

        #abpgAboutPage .abpg-sec-head p {
            margin: 0;
            color: var(--muted);
            font-weight: 650;
            line-height: 1.75;
        }

        #abpgAboutPage .abpg-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 14px 18px;
            border-radius: 16px;
            font-weight: 900;
            text-decoration: none;
            border: 1px solid var(--border);
            box-shadow: 0 14px 34px rgba(0, 0, 0, .08);
            transition: 220ms ease;
            cursor: pointer;
            white-space: nowrap;
        }

        #abpgAboutPage .abpg-btn i {
            font-size: 16px;
        }

        #abpgAboutPage .abpg-btn-primary {
            background: #d85f0f;
            color: #fff;
            border-color: rgba(38, 61, 24, .25);
        }

        #abpgAboutPage .abpg-btn-primary:hover {
            transform: translateY(-2px);
            background: var(--accent);
            color: var(--text);
            border-color: rgba(241, 204, 36, .9);
            box-shadow: 0 18px 44px rgba(0, 0, 0, .12);
        }

        #abpgAboutPage .abpg-btn-outline {
            background: #fff;
            color: #293879;
        }

        #abpgAboutPage .abpg-btn-outline:hover {
            transform: translateY(-2px);
            border-color: rgba(241, 204, 36, .85);
            box-shadow: 0 18px 44px rgba(0, 0, 0, .12);
        }

        #abpgAboutPage .abpg-btn-block {
            width: 100%;
        }

        /* HERO */
        #abpgAboutPage .abpg-hero {
            position: relative;
            padding: 78px 0 66px;
            overflow: hidden;
            background: #0f130e;
        }

        #abpgAboutPage .abpg-hero-bg {
            position: absolute;
            inset: 0;
            background-image: url("{{ $aboutPage->hero_image ? asset($aboutPage->hero_image) : asset('images/about-banner.webp') }}");
            background-size: cover;
            background-position: center;
            transform: scale(1.04);
            filter: saturate(1.03);
        }

        #abpgAboutPage .abpg-hero-overlay {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 18% 20%, rgba(241, 204, 36, .22), transparent 55%),
                radial-gradient(circle at 82% 70%, rgba(38, 61, 24, .35), transparent 60%),
                linear-gradient(180deg, rgba(0, 0, 0, .62), rgba(0, 0, 0, .70));
        }

        #abpgAboutPage .abpg-hero-inner {
            position: relative;
            z-index: 2;
            display: flex;
            justify-content: center;
        }

        #abpgAboutPage .abpg-hero-content {
            text-align: center;
            max-width: 940px;
            color: #fff;
        }

        #abpgAboutPage .abpg-hero-content .abpg-kicker {
            background: rgba(241, 204, 36, .14);
            border-color: rgba(241, 204, 36, .28);
            color: #fff;
        }

        #abpgAboutPage .abpg-hero-content h1 {
            margin: 14px 0 12px;
            font-size: clamp(28px, 4vw, 35px);
            font-weight: 950;
            line-height: 1.05;
            letter-spacing: -.8px;
        }

        #abpgAboutPage .abpg-hero-content p {
            margin: 0 auto 18px;
            max-width: 780px;
            color: rgba(255, 255, 255, .86);
            font-weight: 650;
            line-height: 1.8;
        }

        #abpgAboutPage .abpg-hero-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 6px;
        }

        #abpgAboutPage .abpg-hero-badges {
            margin-top: 22px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }

        #abpgAboutPage .abpg-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .10);
            border: 1px solid rgba(255, 255, 255, .16);
            color: rgba(255, 255, 255, .92);
            font-weight: 800;
            font-size: 13px;
            backdrop-filter: blur(6px);
        }

        /* OVERVIEW */
        #abpgAboutPage .abpg-overview {
            padding: 72px 0;
            background: #fff;
        }

        #abpgAboutPage .abpg-ov-grid {
            display: grid;
            grid-template-columns: 1.06fr .94fr;
            gap: 26px;
            align-items: center;
        }

        #abpgAboutPage .abpg-ov-left h2 {
            margin: 12px 0 10px;
            font-size: clamp(26px, 3.2vw, 42px);
            font-weight: 950;
            color: #293879;
            line-height: 1.1;
        }

        #abpgAboutPage .abpg-ov-left p {
            margin: 0 0 16px;
            color: var(--muted);
            font-weight: 650;
            line-height: 1.8;
        }

        #abpgAboutPage .abpg-checkgrid {
            display: grid;
            gap: 12px;
            margin: 14px 0 18px;
        }

        #abpgAboutPage .abpg-check {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            padding: 14px;
            border-radius: 18px;
            background: linear-gradient(180deg, #fff, var(--soft2));
            border: 1px solid rgba(0, 0, 0, .06);
            transition: 220ms ease;
        }

        #abpgAboutPage .abpg-check:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 44px rgba(0, 0, 0, .08);
            border-color: rgba(241, 204, 36, .55);
        }

        #abpgAboutPage .abpg-check-ico {
            width: 40px;
            height: 40px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(241, 204, 36, .18);
            color: #7a5b00;
            flex: 0 0 40px;
        }

        #abpgAboutPage .abpg-check strong {
            display: block;
            font-weight: 900;
            margin-bottom: 2px;
        }

        #abpgAboutPage .abpg-check span {
            display: block;
            color: var(--muted);
            font-weight: 650;
            line-height: 1.6;
            font-size: 14px;
        }

        #abpgAboutPage .abpg-ov-right {
            position: relative;
        }

        #abpgAboutPage .abpg-ov-media {
            border-radius: 26px;
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, .10);
            box-shadow: var(--shadow2);
            height: 420px;
            background:
                radial-gradient(circle at 30% 30%, rgba(241, 204, 36, 0.18), transparent 60%),
                radial-gradient(circle at 70% 70%, rgba(38, 61, 24, 0.14), transparent 62%),
                url("https://images.unsplash.com/photo-1528826194825-0c25fef2f1f0?auto=format&fit=crop&w=1600&q=75");
            background-size: cover;
            background-position: center;
        }

        #abpgAboutPage .abpg-ov-mini {
            position: absolute;
            left: 16px;
            bottom: 16px;
            background: rgba(255, 255, 255, .92);
            border: 1px solid rgba(0, 0, 0, .08);
            border-radius: 16px;
            padding: 12px 14px;
            box-shadow: 0 16px 40px rgba(0, 0, 0, .12);
        }

        #abpgAboutPage .abpg-ov-mini strong {
            display: block;
            color: var(--brand);
            font-weight: 950;
        }

        #abpgAboutPage .abpg-ov-mini span {
            display: block;
            margin-top: 2px;
            color: var(--muted);
            font-weight: 750;
            font-size: 13px;
        }

        /* USPS */
        #abpgAboutPage .abpg-usps {
            padding: 72px 0;
            background:
                radial-gradient(circle at 18% 20%, rgba(241, 204, 36, 0.12), transparent 55%),
                radial-gradient(circle at 82% 70%, rgba(38, 61, 24, 0.10), transparent 58%),
                linear-gradient(180deg, #ffffff, #f6f8f2);
        }

        #abpgAboutPage .abpg-usps-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
        }

        #abpgAboutPage .abpg-usp-card {
            padding: 22px;
            border-radius: 22px;
            background: #fff;
            border: 1px solid rgba(0, 0, 0, .08);
            box-shadow: var(--shadow);
            transition: 220ms ease;
            position: relative;
            overflow: hidden;
        }

        #abpgAboutPage .abpg-usp-card:before {
            content: "";
            position: absolute;
            inset: -2px;
            background: linear-gradient(120deg, rgba(241, 204, 36, .18), transparent 40%, rgba(38, 61, 24, .14));
            opacity: 0;
            transition: 220ms ease;
        }

        #abpgAboutPage .abpg-usp-card:hover {
            transform: translateY(-7px);
            border-color: rgba(241, 204, 36, .65);
            box-shadow: var(--shadow2);
        }

        #abpgAboutPage .abpg-usp-card:hover:before {
            opacity: 1
        }

        #abpgAboutPage .abpg-usp-card>* {
            position: relative;
            z-index: 1
        }

        #abpgAboutPage .abpg-usp-ico {
            width: 52px;
            height: 52px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(241, 204, 36, .18);
            color: #7a5b00;
            margin-bottom: 12px;
        }

        #abpgAboutPage .abpg-usp-ico i {
            font-size: 22px
        }

        #abpgAboutPage .abpg-usp-card h3 {
            margin: 0 0 6px 0;
            font-weight: 950;
            color: #293879;
        }

        #abpgAboutPage .abpg-usp-card p {
            margin: 0;
            color: var(--muted);
            font-weight: 650;
            line-height: 1.7;
        }

        /* COUNTERS */
        #abpgAboutPage .abpg-counters {
            padding: 62px 0;
            background: #fff;
        }

        #abpgAboutPage .abpg-counter-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
        }

        #abpgAboutPage .abpg-counter-card {
            border-radius: 22px;
            padding: 22px;
            background: #fff;
            border: 1px solid rgba(0, 0, 0, .08);
            box-shadow: var(--shadow);
            text-align: left;
            transition: 220ms ease;
            display: flex;
            gap: 14px;
            align-items: center;
        }

        #abpgAboutPage .abpg-counter-card:hover {
            transform: translateY(-6px);
            border-color: rgba(241, 204, 36, .65);
            box-shadow: var(--shadow2);
        }

        #abpgAboutPage .abpg-counter-ico {
            width: 56px;
            height: 56px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(38, 61, 24, .08);
            color: var(--brand);
            flex: 0 0 56px;
        }

        #abpgAboutPage .abpg-counter-ico i {
            font-size: 22px
        }

        #abpgAboutPage .abpg-counter-num {
            font-size: 30px;
            font-weight: 950;
            color: var(--brand);
            line-height: 1.05;
        }

        #abpgAboutPage .abpg-counter-txt {
            margin-top: 3px;
            color: var(--muted);
            font-weight: 750;
            font-size: 13px;
        }

        /* WHY */
        #abpgAboutPage .abpg-why {
            padding: 72px 0;
            background: #fff;
        }

        #abpgAboutPage .abpg-why-grid {
            display: grid;
            grid-template-columns: 1.05fr .95fr;
            gap: 18px;
            align-items: start;
        }

        #abpgAboutPage .abpg-why-card {
            border-radius: 26px;
            padding: 18px;
            border: 1px solid rgba(0, 0, 0, .08);
            box-shadow: var(--shadow);
            background: linear-gradient(180deg, #ffffff, #f7f9f4);
        }

        #abpgAboutPage .abpg-why-row {
            display: flex;
            gap: 12px;
            padding: 16px 14px;
            border-radius: 18px;
            background: #fff;
            border: 1px solid rgba(0, 0, 0, .06);
            margin-bottom: 12px;
            transition: 220ms ease;
        }

        #abpgAboutPage .abpg-why-row:hover {
            transform: translateY(-2px);
            border-color: rgba(241, 204, 36, .55);
            box-shadow: 0 18px 44px rgba(0, 0, 0, .08);
        }

        #abpgAboutPage .abpg-why-row:last-child {
            margin-bottom: 0;
        }

        #abpgAboutPage .abpg-why-check {
            width: 40px;
            height: 40px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(241, 204, 36, .18);
            color: #7a5b00;
            flex: 0 0 40px;
        }

        #abpgAboutPage .abpg-why-row h3 {
            margin: 0 0 4px;
            font-weight: 950;
            color: var(--brand);
        }

        #abpgAboutPage .abpg-why-row p {
            margin: 0;
            color: var(--muted);
            font-weight: 650;
            line-height: 1.65;
        }

        #abpgAboutPage .abpg-why-sidebox {
            border-radius: 26px;
            padding: 22px;
            border: 1px solid rgba(0, 0, 0, .08);
            box-shadow: var(--shadow);
            background:
                radial-gradient(circle at 20% 20%, rgba(241, 204, 36, 0.16), transparent 55%),
                linear-gradient(180deg, #ffffff, #f6f8f2);
        }

        #abpgAboutPage .abpg-why-sidebox h3 {
            margin: 12px 0 8px;
            font-weight: 950;
            color: var(--brand);
        }

        #abpgAboutPage .abpg-why-sidebox p {
            margin: 0 0 14px;
            color: var(--muted);
            font-weight: 650;
            line-height: 1.75;
        }

        /* TEAM */
        #abpgAboutPage .abpg-team {
            padding: 72px 0;
            background:
                radial-gradient(circle at 18% 20%, rgba(241, 204, 36, 0.12), transparent 55%),
                radial-gradient(circle at 82% 70%, rgba(38, 61, 24, 0.10), transparent 58%),
                linear-gradient(180deg, #ffffff, #f6f8f2);
        }

        #abpgAboutPage .abpg-team-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
        }

        #abpgAboutPage .abpg-team-card {
            border-radius: 22px;
            overflow: hidden;
            background: #fff;
            border: 1px solid rgba(0, 0, 0, .08);
            box-shadow: var(--shadow);
            transition: 220ms ease;
        }

        #abpgAboutPage .abpg-team-card:hover {
            transform: translateY(-7px);
            border-color: rgba(241, 204, 36, .65);
            box-shadow: var(--shadow2);
        }

        #abpgAboutPage .abpg-team-img {
            height: 220px;
            background-size: cover;
            background-position: center;
        }

        #abpgAboutPage .abpg-team-info {
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        #abpgAboutPage .abpg-team-info strong {
            color: var(--brand);
            font-weight: 950;
        }

        #abpgAboutPage .abpg-team-info span {
            color: var(--muted);
            font-weight: 750;
            font-size: 13px;
        }

        #abpgAboutPage .abpg-team-img.t1 {
            background-image: url("https://images.unsplash.com/photo-1607746882042-944635dfe10e?auto=format&fit=crop&w=900&q=75");
        }

        #abpgAboutPage .abpg-team-img.t2 {
            background-image: url("https://images.unsplash.com/photo-1599566150163-29194dcaad36?auto=format&fit=crop&w=900&q=75");
        }

        #abpgAboutPage .abpg-team-img.t3 {
            background-image: url("https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=900&q=75");
        }

        #abpgAboutPage .abpg-team-img.t4 {
            background-image: url("https://images.unsplash.com/photo-1544723795-3fb6469f5b39?auto=format&fit=crop&w=900&q=75");
        }

        #abpgAboutPage .abpg-faq {
            padding: 72px 0;
            background: #fff;
        }

        #abpgAboutPage .abpg-faq-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
            align-items: start;
        }

        #abpgAboutPage .abpg-faq-col {
            display: grid;
            gap: 12px;
        }

        #abpgAboutPage .abpg-faq-item {
            border-radius: 18px;
            border: 1px solid rgba(0, 0, 0, .08);
            background: #fff;
            overflow: hidden;
            box-shadow: 0 16px 46px rgba(0, 0, 0, .06);
            transition: 220ms ease;
        }

        #abpgAboutPage .abpg-faq-q {
            width: 100%;
            text-align: left;
            padding: 16px 16px;
            border: 0;
            background: linear-gradient(180deg, #ffffff, #fbfcf8);
            cursor: pointer;
            font-weight: 900;
            color: var(--brand);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
        }

        #abpgAboutPage .abpg-faq-q:hover {
            background: rgba(241, 204, 36, 0.10);
        }

        #abpgAboutPage .abpg-faq-ico {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(38, 61, 24, .07);
            color: var(--brand);
            transition: 200ms ease;
            flex: 0 0 36px;
        }

        #abpgAboutPage .abpg-faq-a {
            display: none;
            padding: 0 16px 16px;
            color: var(--muted);
            font-weight: 650;
            line-height: 1.75;
            border-top: 1px solid rgba(0, 0, 0, .06);
        }

        #abpgAboutPage .abpg-faq-item.abpg-open {
            border-color: rgba(241, 204, 36, .65);
            box-shadow: 0 22px 62px rgba(0, 0, 0, .10);
        }

        #abpgAboutPage .abpg-faq-item.abpg-open .abpg-faq-a {
            display: block;
        }

        #abpgAboutPage .abpg-faq-item.abpg-open .abpg-faq-ico {
            transform: rotate(180deg);
            background: rgba(241, 204, 36, .18);
            color: #7a5b00;
        }

        /* Responsive */
        @media (max-width:980px) {
            #abpgAboutPage .abpg-faq-grid {
                grid-template-columns: 1fr;
            }
        }

        /* STRAP */
        #abpgAboutPage .abpg-strap {
            padding: 72px 0 84px;
            background:
                radial-gradient(circle at 18% 20%, rgba(241, 204, 36, 0.14), transparent 55%),
                radial-gradient(circle at 82% 70%, rgba(38, 61, 24, 0.10), transparent 58%),
                linear-gradient(180deg, #ffffff, #f6f8f2);
        }

        #abpgAboutPage .abpg-strap-box {
            border-radius: 26px;
            border: 1px solid rgba(0, 0, 0, .08);
            box-shadow: 0 22px 70px rgba(0, 0, 0, .10);
            background: linear-gradient(180deg, #ffffff, #f7f9f4);
            padding: 26px;
            display: grid;
            grid-template-columns: 1.05fr .95fr;
            gap: 18px;
            align-items: center;
        }

        #abpgAboutPage .abpg-strap-left h2 {
            margin: 12px 0 6px;
            font-weight: 950;
            color: var(--brand);
            font-size: clamp(22px, 2.8vw, 34px);
            line-height: 1.12;
        }

        #abpgAboutPage .abpg-strap-left p {
            margin: 0;
            color: var(--muted);
            font-weight: 650;
            line-height: 1.75;
        }

        #abpgAboutPage .abpg-strap-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        #abpgAboutPage .abpg-field {
            position: relative;
        }

        #abpgAboutPage .abpg-field i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--brand);
            opacity: .9;
        }

        #abpgAboutPage .abpg-field input {
            width: 100%;
            height: 48px;
            border-radius: 14px;
            border: 1px solid rgba(0, 0, 0, .12);
            padding: 0 12px 0 40px;
            outline: none;
            font-weight: 650;
            background: #fff;
        }

        #abpgAboutPage .abpg-field input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 4px rgba(241, 204, 36, .16);
        }

        #abpgAboutPage .abpg-strap-btn {
            grid-column: 1/-1;
            height: 50px;
            border-radius: 14px;
        }

        /* Responsive */
        @media (max-width:980px) {
            #abpgAboutPage .abpg-usps-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            #abpgAboutPage .abpg-ov-grid {
                grid-template-columns: 1fr;
            }

            #abpgAboutPage .abpg-counter-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            #abpgAboutPage .abpg-why-grid {
                grid-template-columns: 1fr;
            }

            #abpgAboutPage .abpg-team-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            #abpgAboutPage .abpg-strap-box {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width:560px) {
            #abpgAboutPage .container {
                padding: 0 16px;
            }

            #abpgAboutPage .abpg-usps-grid {
                grid-template-columns: 1fr;
            }

            #abpgAboutPage .abpg-counter-grid {
                grid-template-columns: 1fr;
            }

            #abpgAboutPage .abpg-team-grid {
                grid-template-columns: 1fr;
            }

            #abpgAboutPage .abpg-strap-form {
                grid-template-columns: 1fr;
            }

            #abpgAboutPage .abpg-hero {
                padding: 64px 0 56px;
            }

            #abpgAboutPage .abpg-hero-content p {
                font-size: 14px;
            }
        }
    </style>
    <main class="abpg-wrap" id="abpgAboutPage">
        <!-- HERO -->
        <section class="abpg-hero" id="abpgHero">
            <div class="abpg-hero-bg"></div>
            <div class="abpg-hero-overlay"></div>
            <div class="container abpg-hero-inner">
                <div class="abpg-hero-content">
                    <span class="abpg-kicker"><i class="fa-solid fa-leaf"></i> About Us</span>
                    <h1>{{ $aboutPage->hero_title ?? 'Premium Quality. Clean Sourcing. Honest Process.' }}</h1>
                    <p>
                        {{ $aboutPage->hero_description ?? 'We build everyday essentials with a refined, premium feel—clean ingredients.' }}
                    </p>
                    <div class="abpg-hero-actions">
                        @if ($aboutPage->hero_button_1_text)
                            <a href="{{ $aboutPage->hero_button_1_link ?? '#' }}" class="abpg-btn abpg-btn-primary">
                                <i class="fa-solid fa-bag-shopping"></i> {{ $aboutPage->hero_button_1_text }}
                            </a>
                        @endif
                        @if ($aboutPage->hero_button_2_text)
                            <a href="{{ $aboutPage->hero_button_2_link ?? '#' }}" class="abpg-btn abpg-btn-outline">
                                <i class="fa-solid fa-phone"></i> {{ $aboutPage->hero_button_2_text }}
                            </a>
                        @endif
                    </div>
                    @if ($aboutPage->hero_badges && count($aboutPage->hero_badges) > 0)
                        <div class="abpg-hero-badges">
                            @foreach ($aboutPage->hero_badges as $badge)
                                <span class="abpg-badge"><i class="{{ $badge['icon'] ?? 'fa-solid fa-check' }}"></i>
                                    {{ $badge['text'] }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </section>
        <!-- OVERVIEW -->
        <section class="abpg-overview" id="abpgOverview">
            <div class="container abpg-ov-grid">
                <div class="abpg-ov-left">
                    <span class="abpg-kicker"><i class="fa-solid fa-eye"></i> Overview</span>
                    <h2>{{ $aboutPage->overview_title ?? 'We focus on quality you can feel—everyday.' }}</h2>
                    <p>
                        {{ $aboutPage->overview_description ?? 'Our approach is simple: choose better inputs, maintain clean processing standards, and deliver products that feel premium, consistent and trustworthy.' }}
                    </p>
                    @if ($aboutPage->overview_checks && count($aboutPage->overview_checks) > 0)
                        <div class="abpg-checkgrid">
                            @foreach ($aboutPage->overview_checks as $check)
                                <div class="abpg-check">
                                    <div class="abpg-check-ico"><i class="{{ $check['icon'] ?? 'fa-solid fa-check' }}"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $check['title'] }}</strong>
                                        <span>{{ $check['description'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    @if ($aboutPage->overview_button_text)
                        <a href="{{ $aboutPage->overview_button_link ?? '#' }}" class="abpg-btn abpg-btn-outline">
                            <i class="fa-solid fa-envelope"></i> {{ $aboutPage->overview_button_text }}
                        </a>
                    @endif
                </div>
                <div class="abpg-ov-right">
                    <div class="abpg-ov-media" aria-label="About image">
                        @if ($aboutPage->overview_image)
                            <img src="{{ asset($aboutPage->overview_image) }}" alt="Overview">
                        @else
                            <img src="{{ asset('images/overviews.png') }}" alt="Overview">
                        @endif
                    </div>
                    @if ($aboutPage->overview_badge_rating || $aboutPage->overview_badge_text)
                        <div class="abpg-ov-mini">
                            <strong><i class="fa-solid fa-star"></i>
                                {{ $aboutPage->overview_badge_rating ?? '4.8/5' }}</strong>
                            <span>{{ $aboutPage->overview_badge_text ?? 'Average customer rating' }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </section>
        <!-- USPS -->
        <section class="abpg-usps" id="abpgUsps">
            <div class="container">
                <div class="abpg-sec-head">
                    <span class="abpg-kicker"><i class="fa-solid fa-gem"></i> Highlights</span>
                    <h2>What makes us different</h2>
                    <p>Simple promises, delivered consistently—premium experience without the noise.</p>
                </div>
                <div class="abpg-usps-grid">
                    <div class="abpg-usp-card">
                        <div class="abpg-usp-ico"><i class="fa-solid fa-location-dot"></i></div>
                        <h3>Transparent Sourcing</h3>
                        <p>Clear inputs and clear standards so you always know what you’re buying.</p>
                    </div>
                    <div class="abpg-usp-card">
                        <div class="abpg-usp-ico"><i class="fa-solid fa-certificate"></i></div>
                        <h3>Consistency</h3>
                        <p>Quality that remains reliable across batches—taste, freshness and results.</p>
                    </div>
                    <div class="abpg-usp-card">
                        <div class="abpg-usp-ico"><i class="fa-solid fa-sparkles"></i></div>
                        <h3>Premium Feel</h3>
                        <p>From packaging to experience—everything is designed to feel refined.</p>
                    </div>
                    <div class="abpg-usp-card">
                        <div class="abpg-usp-ico"><i class="fa-solid fa-headset"></i></div>
                        <h3>Responsive Support</h3>
                        <p>Quick help, clear communication, and smooth purchase experience.</p>
                    </div>
                </div>
            </div>
        </section>
        <!-- COUNTERS -->
        <section class="abpg-counters" id="abpgCounters">
            <div class="container">
                <div class="abpg-sec-head abpg-sec-head-left">
                    <span class="abpg-kicker"><i class="fa-solid fa-chart-simple"></i> Proof</span>
                    <h2>Numbers that reflect trust</h2>
                    <p>Consistent quality builds long-term loyalty.</p>
                </div>
                @if ($aboutPage->counters && count($aboutPage->counters) > 0)
                    <div class="abpg-counter-grid">
                        @foreach ($aboutPage->counters as $counter)
                            <div class="abpg-counter-card">
                                <div class="abpg-counter-ico"><i class="{{ $counter['icon'] ?? 'fa-solid fa-star' }}"></i>
                                </div>
                                <div>
                                    <div class="abpg-counter-num" data-target="{{ $counter['number'] ?? '0' }}">0</div>
                                    <div class="abpg-counter-txt">{{ $counter['text'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
        <!-- WHY -->
        <section class="abpg-why" id="abpgWhy">
            <div class="container">
                <div class="abpg-sec-head abpg-sec-head-left">
                    <span class="abpg-kicker"><i class="fa-solid fa-circle-check"></i> Why Us</span>
                    <h2>Why choose us</h2>
                    <p>Clean standards + premium experience—built for daily trust.</p>
                </div>
                <div class="abpg-why-grid">
                    @if ($aboutPage->why_items && count($aboutPage->why_items) > 0)
                        <div class="abpg-why-card">
                            @foreach ($aboutPage->why_items as $item)
                                <div class="abpg-why-row">
                                    <span class="abpg-why-check"><i class="fa-solid fa-check"></i></span>
                                    <div>
                                        <h3>{{ $item['title'] }}</h3>
                                        <p>{{ $item['description'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <div class="abpg-why-side">
                        <div class="abpg-why-sidebox">
                            <span class="abpg-kicker"><i class="fa-solid fa-shield"></i> Our Promise</span>
                            <h3>{{ $aboutPage->why_promise_title ?? 'Premium experience, every order.' }}</h3>
                            <p>{{ $aboutPage->why_promise_description ?? 'We deliver consistent quality and premium experience with every order.' }}
                            </p>
                            @if ($aboutPage->why_promise_button_text)
                                <a href="{{ $aboutPage->why_promise_button_link ?? '#' }}"
                                    class="abpg-btn abpg-btn-primary abpg-btn-block">
                                    <i class="fa-solid fa-cart-shopping"></i> {{ $aboutPage->why_promise_button_text }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- TEAM -->
        <section class="abpg-team" id="abpgTeam">
            <div class="container">
                <div class="abpg-sec-head">
                    <span class="abpg-kicker"><i class="fa-solid fa-people-group"></i> Team</span>
                    <h2>Meet our team</h2>
                    <p>People behind quality, consistency and customer experience.</p>
                </div>
                @if ($aboutPage->team_members && count($aboutPage->team_members) > 0)
                    <div class="abpg-team-grid">
                        @foreach ($aboutPage->team_members as $member)
                            <div class="abpg-team-card">
                                <div class="abpg-team-img"
                                    style="background-image:url('{{ asset($member['image'] ?? 'images/placeholder.jpg') }}');">
                                </div>
                                <div class="abpg-team-info">
                                    <strong>{{ $member['name'] }}</strong>
                                    <span>{{ $member['role'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
        <!-- FAQ (optional, add if you want) -->
        <section class="abpg-faq" id="abpgFaq">
            <div class="container">
                <div class="abpg-sec-head">
                    <span class="abpg-kicker"><i class="fa-solid fa-circle-question"></i> FAQ</span>
                    <h2>Quick answers</h2>
                    <p>Everything you may want to know before you order.</p>
                </div>
                @if ($aboutPage->faqs && count($aboutPage->faqs) > 0)
                    <div class="abpg-faq-grid">
                        @php
                            $half = ceil(count($aboutPage->faqs) / 2);
                            $leftFaqs = array_slice($aboutPage->faqs, 0, $half);
                            $rightFaqs = array_slice($aboutPage->faqs, $half);
                        @endphp
                        <!-- LEFT -->
                        <div class="abpg-faq-col">
                            @foreach ($leftFaqs as $faq)
                                <div class="abpg-faq-item">
                                    <button class="abpg-faq-q" type="button">
                                        {{ $faq['question'] }}
                                        <span class="abpg-faq-ico"><i class="fa-solid fa-chevron-down"></i></span>
                                    </button>
                                    <div class="abpg-faq-a">
                                        {{ $faq['answer'] }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- RIGHT -->
                        <div class="abpg-faq-col">
                            @foreach ($rightFaqs as $faq)
                                <div class="abpg-faq-item">
                                    <button class="abpg-faq-q" type="button">
                                        {{ $faq['question'] }}
                                        <span class="abpg-faq-ico"><i class="fa-solid fa-chevron-down"></i></span>
                                    </button>
                                    <div class="abpg-faq-a">
                                        {{ $faq['answer'] }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </section>
        <style>
            #abpgAboutPage .abpg-field-textarea textarea {
                width: 100%;
                min-height: 40px;
                border-radius: 14px;
                border: 1px solid rgba(0, 0, 0, .12);
                padding: 12px 12px 12px 40px;
                outline: none;
                font-weight: 650;
                font-family: inherit;
                resize: vertical;
                background: #fff;
            }

            #abpgAboutPage .abpg-field-textarea textarea:focus {
                border-color: var(--accent);
                box-shadow: 0 0 0 4px rgba(241, 204, 36, .16);
            }
        </style>
        <!-- STRAP FORM -->
        <section class="abpg-strap" id="abpgStrap">
            <div class="container">
                <div class="abpg-strap-box">
                    <div class="abpg-strap-left">
                        <span class="abpg-kicker"><i class="fa-solid fa-message"></i> Let’s connect</span>
                        <h2>{{ $aboutPage->contact_form_title ?? 'Want help choosing the right products?' }}</h2>
                        <p>{{ $aboutPage->contact_form_description ?? 'Share your details and our team will reach out shortly.' }}
                        </p>
                    </div>
                    <form class="abpg-strap-form" action="#" method="post">
                        <div class="abpg-field">
                            <i class="fa-solid fa-user"></i>
                            <input type="text" name="name" placeholder="Your Name" required>
                        </div>
                        <div class="abpg-field">
                            <i class="fa-solid fa-envelope"></i>
                            <input type="email" name="email" placeholder="Email Address" required>
                        </div>
                        <div class="abpg-field">
                            <i class="fa-solid fa-phone"></i>
                            <input type="tel" name="phone" placeholder="Phone Number" required pattern="[0-9]{10}"
                                maxlength="10">
                        </div>
                        <div class="abpg-field abpg-field-textarea">
                            <i class="fa-solid fa-message"></i>
                            <textarea name="message" placeholder="Your Message" required rows="1"></textarea>
                        </div>
                        <button class="abpg-btn abpg-btn-primary abpg-strap-btn" type="submit">
                            <i class="fa-solid fa-paper-plane"></i> Submit
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </main>
    <script>
        (function() {
            const root = document.getElementById("abpgAboutPage");
            if (!root) return;

            // FAQ
            root.querySelectorAll(".abpg-faq-item .abpg-faq-q").forEach((btn) => {
                btn.addEventListener("click", () => {
                    const item = btn.closest(".abpg-faq-item");
                    const wrap = item.parentElement;

                    wrap.querySelectorAll(".abpg-faq-item").forEach(i => {
                        if (i !== item) i.classList.remove("abpg-open");
                    });

                    item.classList.toggle("abpg-open");
                });
            });

            // Counter
            const counters = root.querySelectorAll(".abpg-counter-num");
            const runCounter = (el) => {
                const target = parseInt(el.getAttribute("data-target"), 10) || 0;
                const duration = 1100;
                const start = performance.now();
                const from = 0;

                const step = (t) => {
                    const p = Math.min((t - start) / duration, 1);
                    const val = Math.floor(from + (target - from) * (1 - Math.pow(1 - p, 3)));
                    el.textContent = val.toLocaleString();
                    if (p < 1) requestAnimationFrame(step);
                };
                requestAnimationFrame(step);
            };

            const io = new IntersectionObserver((entries) => {
                entries.forEach((e) => {
                    if (e.isIntersecting) {
                        runCounter(e.target);
                        io.unobserve(e.target);
                    }
                });
            }, {
                threshold: 0.35
            });

            counters.forEach(c => io.observe(c));
        })();
    </script>
@endsection
