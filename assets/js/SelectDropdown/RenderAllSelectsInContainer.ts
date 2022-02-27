import Render from './Render';

export default (container: Element) => {
    const selectDropdowns = container.getElementsByClassName(
        'ansel_select_dropdown',
    );

    for (let i = 0; i < selectDropdowns.length; i += 1) {
        Render(selectDropdowns[i]);
    }
};
