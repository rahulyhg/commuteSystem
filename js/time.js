
        var checkintime;
        var checkouttime;
        var intervaltime;

        // Time Setting
        function clock() {
                var data = new Date();

                // 時間と関連された情報を保存します。
                hours = data.getHours();
                minutes = data.getMinutes();
                seconds = data.getSeconds();
                timeStr = ((hours < 10) ? "0" : "") + hours;
                timeStr += ((minutes < 10) ? ":0" : ":") + minutes;
                timeStr += ((seconds < 10) ? ":0" : ":") + seconds;

                // フォームの時間を表示する入力欄に文字列を出力します。
                document.clock.time.value = timeStr;

                // 日付と関連された情報を保存します。
                months = data.getMonth()+1;  // monthは0から始まる
                days = data.getDate();
                years = data.getFullYear();
                dateStr = years;
                dateStr += ((months < 10) ? "0" : "") + months;
                dateStr += ((days < 10) ? "0" : "") + days;

                months2 = data.getMonth()+1;
                days2 = data.getDate();
                years2 = data.getFullYear();
                dateStr2 = years;
                dateStr2 += ((months2 < 10) ? "/0" : "/") + months2;
                dateStr2 += ((days2 < 10) ? "/0" : "/") + days2;


                Attendances_mounth_date = dateStr.substr(0,6);

                // フォームの日付を表示する入力欄に文字列を出力します。
                document.clock.date.value = dateStr;
                document.clock.date2.value = dateStr2;
                // １秒ごとに日付と時間が変わる
                Timer = setTimeout("clock()", 1000);


                // ここから神立打ち //

                //----時間の割り当て----//

                var faiz_1;
                var faiz_2;
                var faiz_3;
                var faiz_4;
                var faiz_5;
                var faiz_6;
                var faiz_7;
                var faiz_8;

                if(minutes >= 00 && minutes <= 14){
                  faiz_1 = 15;
                  checkintime = (hours + ":" + faiz_1);
                }

                if(minutes >= 15  && minutes <= 29){
                  faiz_2 = 30;
                  checkintime = (hours + ":" + faiz_2);
                }

                if(minutes >= 30 && minutes <= 44){
                  faiz_3 = 45;
                  checkintime = (hours + ":" + faiz_3);
                }

                if(minutes >= 45 && minutes <= 59){
                  faiz_4 = 00;
                  checkintime = (hours + 1 + ":0" + faiz_4);
                }
//----------------------------------------------------------------------------//
                if(minutes >= 00 && minutes <= 14){
                  faiz_5 = 00;
                  checkouttime = (hours + ":0" + faiz_5);
                }

                if(minutes >=15  && minutes <= 29){
                  faiz_6 = 15;
                  checkouttime = (hours + ":" + faiz_6);
                }

                if(minutes >= 30 && minutes <= 44){
                  faiz_7 = 30;
                  checkouttime = (hours + ":" + faiz_7);
                }

                if(minutes >= 45 && minutes <= 59){
                  faiz_8 = 45;
                  checkouttime = (hours + ":" + faiz_8);
                }

                if(minutes >= 30 && minutes <= 59 && hours == 9){
                  faiz_4 = 00;
                  checkintime = (hours + 1 + ":0" + faiz_4);
                }

                //console.log(faiz_1,faiz_2,faiz_3,faiz_4);
                //console.log(faiz_5,faiz_6,faiz_7,faiz_8);

}

        // end of Time setting -->
