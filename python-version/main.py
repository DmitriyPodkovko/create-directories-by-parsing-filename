from os import path, makedirs


def f_read_input(file_name):
    try:
        with open(file_name, 'r') as f:
            s = f.read()
            print(f'... {file_name} read Ok!')
            return s
    except Exception as e:
        print(f'Error {e.errno} during opening file: {file_name}\n{e}')


def f_add_log(file_name, string):
    try:
        with open(file_name, 'a', encoding='utf-8') as f:
            f.write(string)
            print(f'... {string} added to {file_name} Ok!')
            return True
    except Exception as e:
        print(f'Error {e.errno} during writing to file: {file_name}\n{e}')


def parsing(file_str, start_str, end_str):
    try:
        dic_lst_val = {}
        n = 0
        j = 0
        while n != -1:  #
            n = file_str.find(start_str, n)
            if n > 0:
                k = file_str.find(end_str, n)
                s = f'{file_str[n:k]}{end_str}'  # вырезаем от ... и ... до и добавляем имя файла
                lst_val = s.split('/')
                for i in range(len(lst_val)):  # избавляемся от пробелов
                    lst_val[i] = lst_val[i].strip()
                dic_lst_val[j] = lst_val
                j += 1
            if n != -1:
                n += 1
        if len(dic_lst_val) > 0:
            print(f'... parsing from {start_str} to {end_str} Ok!')
        else:
            print(f'... {start_str} during parsing not found!')
        return dic_lst_val
    except Exception as e:
        print(f'Error {e.errno} during parsing file (into string)!\n{e}')


def make_dir(root_directory, dic_lst_directory, file_log):
    try:
        for i in range(len(dic_lst_directory)):  # собираем путь для создания каталогов
            tmp_lst = dic_lst_directory[i]
            del tmp_lst[0]
            last_val = tmp_lst.pop()
            path_dir = f"{root_directory}/{'/'.join(tmp_lst)}"
            if not path.exists(path_dir):
                makedirs(path_dir)
                print(f'... {path_dir} crated Ok!')
                f_add_log(file_log, f'{path_dir}/{last_val}\n')
            else:
                print(f'... {path_dir} already exists!')
    except Exception as e:
        print(f'Error {e.errno} during making directory!\n{e}')


def func_main():
    print('Start ...')
    config = {}
    exec(open('path.conf').read(), config)
    f_str = f_read_input(config['input'])
    find_start_end = list(config['find'].items())
    for i in range(len(find_start_end)):
        dic_lst_directory = parsing(f_str, find_start_end[i][0], find_start_end[i][1])
        if len(dic_lst_directory) > 0:
            make_dir(config['root_directory'], dic_lst_directory, config['output'])
    print('Finished!')
    input('\nPress ENTER to exit:')


func_main()

