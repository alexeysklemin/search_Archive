/*Задание 7. Меню ресторана
Что нужно сделать
Напишите программу, которая выводит меню бизнес-ланча ресторана в зависимости от дня недели.
В меню есть общая часть, а есть уникальная, которая зависит от дня недели.
Пользователь должен ввести номер дня недели — от 1 (понедельник) до 7 (воскресенье), а программа должна вывести на экран день недели
и меню этого дня.

Пример 1

Введите день недели (от 1 до 7): 4
Меню сегодня (четверг):
Гороховый суп
Салат «Цезарь» с креветками
Куриная ножка с пюре
Морс

Пример 2

Введите день недели (от 1 до 7): 2
Меню сегодня (вторник):
Харчо
Салат «Оливье»
Баварские колбаски с капустой
Морс*/

#include <iostream>

enum weekDay{
    Monday,
    Tuesday,
    Wednesday,
    Thursday,
    Friday,
    Saturday,
    Sunday
};

class EverydayDishes {
public:
    int weekDayNumber=0;
private:
    std::string dishes[7][4] = {"Харчо", "Салат «Оливье»", "Баварские колбаски с капустой", "Морс",
                                "Гороховый суп", "Салат «Цезарь» с креветками ", "Куриная ножка с пюре", "Морс",
                                "Суп", "Салат «Цезарь» с мидиями ", "Баварские колбаски с картошкой", "Морс",
                                "Борщ", "Овощной салат", "Жаркое с картошкой", "Морс",
                                "Солянка", "Салат", "Вермишель с куриным мясом","Морс",
                                "Суп", "Фруктовый салат", "Макароны по флотски","Морс",
                                "Окрошка", "Фруктовый салат", "Фруктовый смузи", "Морс",

                                };
public:

    int setNumerDay(int weekNumberDay){
        return weekNumberDay;
    }


    void getMenu(std::string &dishes, int weekNumberDay){
        std::string menu;
        for (int menu_ = 0; menu_ < 4; ++menu_) {
            std::cout << dishes[weekDayNumber][&menu_] << "\n";
        }

        
    }


};

int  weekDayDefine(std::string day, weekDay today){
    if(day=="Monday" || day=="Mon" || day=="1") today=Monday;
    if(day=="Tuesday" || day=="Tue" || day=="2") today=Tuesday;
    if(day=="Wednesday" || day=="Wed" || day=="3") today=Wednesday;
    if(day=="Thursday" || day=="Thu" || day=="4") today=Thursday;
    if(day=="Friday" || day=="Fri" || day=="5") today=Friday;
    if(day=="Saturday" || day=="Sat" || day=="6") today=Saturday;
    if(day=="Sunday" || day=="Sun" || day=="7") today=Sunday;

    return today;
}


//using namespace std;

int main()
{
    std::string day;
    EverydayDishes everydayDishes;
    std::cin >> day;
    weekDay today = Monday;
    int Day = 0;
    Day = weekDayDefine(day, today);
    std::string dishe[7][4];

    everydayDishes.setNumerDay(Day);
    everydayDishes.getMenu(dishe,day);
        return 0;
}
